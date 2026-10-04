<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrderCompletionTimeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.order_time_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);
        DB::purge('order_time_test');
        DB::setDefaultConnection('order_time_test');

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::setDefaultConnection(config('database.default'));
        DB::purge('order_time_test');

        parent::tearDown();
    }

    public function test_observer_sets_completion_time_once_when_status_becomes_completed(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-03 12:00:00'));
        $observer = new OrderObserver();

        $order = new Order();
        $order->status = 'completed';
        $observer->updating($order);
        $firstCompletionTime = $order->completed_at;

        $this->assertEquals(Carbon::parse('2026-10-03 12:00:00'), $firstCompletionTime);

        Carbon::setTestNow(Carbon::parse('2026-10-03 13:00:00'));
        $order->status = 'processing';
        $observer->updating($order);
        $order->status = 'completed';
        $observer->updating($order);

        $this->assertEquals($firstCompletionTime, $order->completed_at);
    }

    public function test_elapsed_time_formats_under_one_minute(): void
    {
        $this->freezeAt('2026-10-03 12:00:00');

        $this->assertSame('Menos de 1 min', $this->orderCreatedAt('2026-10-03 11:59:30')->elapsed_time_formatted);
    }

    public function test_elapsed_time_formats_minutes(): void
    {
        $this->freezeAt('2026-10-03 12:00:00');

        $this->assertSame('30 min', $this->orderCreatedAt('2026-10-03 11:30:00')->elapsed_time_formatted);
    }

    public function test_elapsed_time_formats_hours(): void
    {
        $this->freezeAt('2026-10-03 12:00:00');

        $this->assertSame('2 h 15 min', $this->orderCreatedAt('2026-10-03 09:45:00')->elapsed_time_formatted);
    }

    public function test_elapsed_time_formats_days(): void
    {
        $this->freezeAt('2026-10-03 12:00:00');

        $this->assertSame('1 d 2 h', $this->orderCreatedAt('2026-10-02 10:00:00')->elapsed_time_formatted);
    }

    public function test_completed_elapsed_time_is_frozen_at_completed_at(): void
    {
        $this->freezeAt('2026-10-03 12:00:00');
        $order = $this->orderCreatedAt('2026-10-03 09:00:00');
        $order->status = 'completed';
        $order->completed_at = Carbon::parse('2026-10-03 10:00:00');

        $this->assertSame('1 h', $order->elapsed_time_formatted);
    }

    private function freezeAt(string $time): void
    {
        Carbon::setTestNow(Carbon::parse($time));
    }

    private function orderCreatedAt(string $time): Order
    {
        $order = new Order();
        $order->status = 'processing';
        $order->created_at = Carbon::parse($time);

        return $order;
    }
}
