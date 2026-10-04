<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClientServiceCatalogTest extends TestCase
{
    private string $originalDefaultConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalDefaultConnection = config('database.default');
        config(['database.connections.client_catalog_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('client_catalog_test');
        DB::setDefaultConnection('client_catalog_test');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('suggested_price', 10, 2)->nullable();
            $table->string('processing_time')->nullable();
            $table->boolean('has_schedule')->default(false);
            $table->json('schedule_days')->nullable();
            $table->time('schedule_start')->nullable();
            $table->time('schedule_end')->nullable();
            $table->boolean('is_maintenance')->default(false);
            $table->string('maintenance_message')->nullable();
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::setDefaultConnection($this->originalDefaultConnection);
        DB::purge('client_catalog_test');

        parent::tearDown();
    }

    public function test_client_catalog_renders_mixed_service_availability_and_null_category(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 18:00:00', 'America/Mexico_City'));

        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'balance' => 100,
            'is_admin' => false,
        ]);
        $category = Category::create(['name' => 'General']);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Servicio normal',
            'price' => 25,
        ]);
        Service::create([
            'category_id' => $category->id,
            'name' => 'Servicio en mantenimiento',
            'price' => 25,
            'is_maintenance' => true,
            'maintenance_message' => 'Regresamos pronto',
        ]);
        Service::create([
            'category_id' => $category->id,
            'name' => 'Servicio fuera de horario',
            'price' => 25,
            'has_schedule' => true,
            'schedule_days' => [1],
            'schedule_start' => '09:00:00',
            'schedule_end' => '17:00:00',
        ]);
        Service::create([
            'name' => 'Servicio sin categoría',
            'price' => 25,
        ]);

        $response = $this->actingAs($client)->get('/app/services');

        $response->assertOk();
        $response->assertSee('Servicio normal');
        $response->assertSee('Servicio en mantenimiento');
        $response->assertSee('Servicio fuera de horario');
        $response->assertSee('Servicio sin categoría');
        $response->assertSee('En mantenimiento');
        $response->assertSee('Regresamos pronto');
    }

    public function test_normal_service_does_not_show_maintenance_label(): void
    {
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'balance' => 100,
            'is_admin' => false,
        ]);
        $category = Category::create(['name' => 'General']);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Servicio normal',
            'price' => 25,
        ]);

        $response = $this->actingAs($client)->get('/app/services');

        $response->assertOk();
        $response->assertSee('Servicio normal');
        $response->assertDontSee('En mantenimiento');
    }
}