<?php

namespace Tests\Feature;

use App\Filament\Dashboard\Pages\BuyBatchService;
use App\Filament\Dashboard\Pages\BuyService;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class OrderFormValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.order_form_validation_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
        ]);
        DB::purge('order_form_validation_test');
        DB::setDefaultConnection('order_form_validation_test');

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
            $table->string('code')->nullable();
            $table->string('name')->default('Test Service');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(60);
            $table->decimal('cost', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('has_schedule')->default(false);
            $table->json('schedule_days')->nullable();
            $table->time('schedule_start')->nullable();
            $table->time('schedule_end')->nullable();
            $table->boolean('is_maintenance')->default(false);
            $table->string('maintenance_message')->nullable();
            $table->json('form_schema')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_id');
            $table->json('input_data')->nullable();
            $table->string('status')->default('pending');
            $table->string('result_file_path')->nullable();
            $table->text('admin_notes')->nullable();
            $table->decimal('price_at_purchase', 10, 2)->default(0);
            $table->decimal('service_cost_snapshot', 10, 2)->nullable();
            $table->decimal('service_price_snapshot', 10, 2)->nullable();
            $table->string('batch_id')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->decimal('amount', 10, 2);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        DB::setDefaultConnection(config('database.default'));
        DB::purge('order_form_validation_test');

        parent::tearDown();
    }

    public function test_buy_service_rejects_missing_lugar_emision_before_creating_order(): void
    {
        $user = User::create(['name' => 'Buyer', 'email' => 'buyer@example.test', 'balance' => 100, 'is_admin' => false]);
        $service = Service::create([
            'code' => 'csf-curp-clon',
            'form_schema' => [
                ['name' => 'curp', 'label' => 'CURP', 'required' => true],
                ['name' => 'lugarEmision', 'label' => 'Lugar', 'required' => true],
            ],
        ]);
        $this->actingAs($user);

        $page = app(BuyService::class);
        $page->service = $service;
        $page->form->fill(['input_data' => ['curp' => 'ABCD010101HYNAAA01', 'lugarEmision' => null]]);

        try {
            $page->submit();
            $this->fail('Expected validation to fail.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('data.input_data.lugarEmision', $exception->errors());
        }

        $this->assertSame(0, Order::count());
    }

    public function test_order_with_missing_lugar_emision_is_rejected_without_calling_docmx_and_refunded(): void
    {
        $docMx = Mockery::mock('overload:App\Services\DocMxService');
        $docMx->shouldReceive('missingFields')->andReturn(['lugarEmision']);
        $docMx->shouldReceive('normalizeFormData')->andReturnUsing(
            fn (string $serviceCode, array $formData): array => $formData
        );
        $docMx->shouldReceive('canonicalFieldName')->andReturnUsing(
            fn (string $serviceCode, string $fieldName): string =>
                strcasecmp($fieldName, 'lugarEmision') === 0 ? 'lugarEmision' : $fieldName
        );
        $docMx->shouldReceive('canonicalFields')->with('csf-curp-clon')->andReturn(['curp', 'lugarEmision']);
        $docMx->shouldNotReceive('createOrder');

        $user = User::create(['name' => 'Buyer', 'email' => 'buyer@example.test', 'balance' => 100, 'is_admin' => false]);
        $service = Service::create([
            'code' => 'csf-curp-clon',
            'price' => 60,
            'form_schema' => [
                ['name' => 'curp', 'label' => 'CURP', 'required' => true],
                ['name' => 'lugarEmision', 'label' => 'Lugar', 'required' => false],
            ],
        ]);
        $this->actingAs($user);

        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'input_data' => ['curp' => 'ABCD010101HYNAAA01'],
            'status' => 'pending',
            'price_at_purchase' => 60,
        ]);

        $this->assertSame('rejected', $order->fresh()->status);
        $this->assertSame(100.0, (float) $user->fresh()->balance);
        $this->assertDatabaseHas('transactions', ['type' => 'refund', 'amount' => 60]);
    }

    public function test_order_normalizes_lugaremision_before_sending_to_docmx(): void
    {
        $docMx = Mockery::mock('overload:App\Services\DocMxService');
        $docMx->shouldReceive('missingFields')->andReturn([]);
        $docMx->shouldReceive('normalizeFormData')->andReturnUsing(
            fn (string $serviceCode, array $formData): array => [
                'curp' => $formData['curp'],
                'lugarEmision' => $formData['lugaremision'],
            ]
        );
        $docMx->shouldReceive('canonicalFieldName')->andReturnUsing(
            fn (string $serviceCode, string $fieldName): string =>
                strcasecmp($fieldName, 'lugarEmision') === 0
                    ? 'lugarEmision'
                    : $fieldName
        );
        $docMx->shouldReceive('canonicalFields')->with('csf-curp-clon')->andReturn(['curp', 'lugarEmision']);
        $docMx->shouldReceive('createOrder')
            ->once()
            ->with(
                'csf-curp-clon',
                ['curp' => 'ABCD010101HYNAAA01', 'lugarEmision' => 'Mérida, Yucatán'],
                Mockery::type('string')
            )
            ->andReturn(Http::response(['accepted' => true], 200));

        $user = User::create(['name' => 'Buyer', 'email' => 'buyer@example.test', 'balance' => 100, 'is_admin' => false]);
        $service = Service::create([
            'code' => 'csf-curp-clon',
            'price' => 60,
            'form_schema' => [
                ['name' => 'curp', 'label' => 'CURP', 'required' => true],
                ['name' => 'lugaremision', 'label' => 'Lugar', 'required' => true],
            ],
        ]);
        $this->actingAs($user);

        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'input_data' => [
                'curp' => 'ABCD010101HYNAAA01',
                'lugaremision' => 'Mérida, Yucatán',
            ],
            'status' => 'pending',
            'price_at_purchase' => 60,
        ]);

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_buy_batch_service_rejects_missing_lugar_emision(): void
    {
        $user = User::create(['name' => 'Buyer', 'email' => 'buyer@example.test', 'balance' => 100, 'is_admin' => false]);
        $service = Service::create([
            'code' => 'csf-curp-clon',
            'form_schema' => [
                ['name' => 'curp', 'label' => 'CURP', 'required' => true],
                ['name' => 'lugarEmision', 'label' => 'Lugar', 'required' => true],
            ],
        ]);
        $this->actingAs($user);

        $page = app(BuyBatchService::class);
        $page->service = $service;
        $page->form->fill([
            'requests' => [
                ['curp' => 'ABCD010101HYNAAA01', 'lugarEmision' => null],
            ],
        ]);

        try {
            $page->submit();
            $this->fail('Expected validation to fail.');
        } catch (ValidationException $exception) {
            $rawRows = $page->form->getRawState()['requests'];
            $rowKey = array_key_first($rawRows);

            $this->assertArrayHasKey(
                "data.requests.{$rowKey}.lugarEmision",
                $exception->errors()
            );
        }
    }
}
