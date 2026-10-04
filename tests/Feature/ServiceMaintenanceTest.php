<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Observers\OrderObserver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ServiceMaintenanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.service_maintenance_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);
        DB::purge('service_maintenance_test');
        DB::setDefaultConnection('service_maintenance_test');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->default('Test User');
            $table->string('email')->default('test@example.test');
            $table->string('password')->default('password');
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->decimal('price', 10, 2)->default(25);
            $table->decimal('cost', 10, 2)->default(0);
            $table->boolean('has_schedule')->default(false);
            $table->json('schedule_days')->nullable();
            $table->time('schedule_start')->nullable();
            $table->time('schedule_end')->nullable();
            $table->boolean('is_maintenance')->default(false);
            $table->string('maintenance_message', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('service_id');
            $table->json('input_data')->nullable();
            $table->string('status');
            $table->decimal('price_at_purchase', 10, 2)->default(0);
            $table->decimal('service_price_snapshot', 10, 2)->nullable();
            $table->decimal('service_cost_snapshot', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::setDefaultConnection(config('database.default'));
        DB::purge('service_maintenance_test');

        parent::tearDown();
    }

    public function test_order_creation_is_rejected_in_maintenance_without_order_or_charge(): void
    {
        $userId = DB::table('users')->insertGetId(['balance' => 100]);
        $service = new Service([
            'is_maintenance' => true,
            'maintenance_message' => 'Volvemos mañana a las 9:00',
        ]);
        $service->save();

        try {
            Order::create([
                'user_id' => $userId,
                'service_id' => $service->id,
                'input_data' => [],
                'status' => 'pending',
                'price_at_purchase' => 25,
            ]);
            $this->fail('Unavailable service order creation should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertSame('Volvemos mañana a las 9:00', $exception->errors()['service'][0]);
        }

        $this->assertSame(0, DB::table('orders')->count());
        $this->assertSame(100.0, (float) DB::table('users')->where('id', $userId)->value('balance'));
    }

    public function test_admin_can_create_order_for_service_in_maintenance(): void
    {
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'balance' => 100,
            'is_admin' => true,
        ]);
        $service = new Service([
            'is_maintenance' => true,
            'maintenance_message' => 'Volvemos mañana a las 9:00',
        ]);
        $service->save();

        $this->actingAs(\App\Models\User::query()->findOrFail($adminId));

        $order = new Order([
            'user_id' => $adminId,
            'service_id' => $service->id,
            'input_data' => [],
            'status' => 'pending',
            'price_at_purchase' => 0,
        ]);
        $order->setRelation('service', $service);
        (new OrderObserver())->creating($order);
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $order->user_id,
            'service_id' => $order->service_id,
            'input_data' => json_encode($order->input_data),
            'status' => $order->status,
            'price_at_purchase' => $order->price_at_purchase,
            'service_price_snapshot' => $order->service_price_snapshot,
            'service_cost_snapshot' => $order->service_cost_snapshot,
        ]);

        $this->assertDatabaseHas('orders', ['id' => $orderId], 'service_maintenance_test');
    }

    public function test_service_inside_its_schedule_is_available(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 10:00:00', 'America/Mexico_City'));
        $service = new Service([
            'has_schedule' => true,
            'schedule_days' => [1],
            'schedule_start' => '09:00:00',
            'schedule_end' => '17:00:00',
        ]);

        $this->assertTrue($service->isAvailable());
        $this->assertNull($service->unavailableReason());
    }

    public function test_service_outside_its_schedule_remains_blocked(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 18:00:00', 'America/Mexico_City'));
        $service = new Service([
            'has_schedule' => true,
            'schedule_days' => [1],
            'schedule_start' => '09:00:00',
            'schedule_end' => '17:00:00',
        ]);

        $this->assertFalse($service->isAvailable());
        $this->assertSame('Disponible el lunes a las 09:00', $service->unavailableReason());
    }

    public function test_reactivating_service_allows_requests_again(): void
    {
        $service = new Service([
            'is_maintenance' => true,
            'maintenance_message' => 'Mantenimiento programado',
        ]);

        $this->assertFalse($service->isAvailable());
        $service->is_maintenance = false;
        $service->maintenance_message = null;

        $this->assertTrue($service->isAvailable());
        $this->assertNull($service->unavailableReason());
    }
}
