<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class OrderDownloadErrorHandlingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('result_file_path')->nullable();
        });

        DB::table('orders')->insert([
            'id' => 1,
            'result_file_path' => 'order-results/document.pdf',
        ]);
    }

    public function test_storage_failure_returns_503_and_logs_error(): void
    {
        $disk = Mockery::mock();
        $disk->shouldReceive('exists')
            ->once()
            ->with('order-results/document.pdf')
            ->andThrow(new \RuntimeException('S3 unavailable'));

        Storage::shouldReceive('disk')->once()->with('s3')->andReturn($disk);
        Log::shouldReceive('error')
            ->once()
            ->with('Failed to retrieve order document.', Mockery::on(
                fn (array $context): bool => $context['order_id'] === 1
                    && $context['path'] === 'order-results/document.pdf'
                    && $context['exception'] instanceof \RuntimeException
            ));

        $this->get($this->signedDownloadUrl())
            ->assertServiceUnavailable();
    }

    public function test_missing_file_returns_404(): void
    {
        Storage::fake('s3');
        Storage::fake('public');

        $this->get($this->signedDownloadUrl())
            ->assertNotFound();
    }

    public function test_http_exception_from_storage_remains_404_without_error_log(): void
    {
        $disk = Mockery::mock();
        $disk->shouldReceive('exists')
            ->once()
            ->with('order-results/document.pdf')
            ->andThrow(new NotFoundHttpException());

        Storage::shouldReceive('disk')->once()->with('s3')->andReturn($disk);
        Log::shouldReceive('error')->never();

        $this->get($this->signedDownloadUrl())
            ->assertNotFound();
    }

    private function signedDownloadUrl(): string
    {
        return URL::temporarySignedRoute(
            'orders.download',
            now()->addMinutes(5),
            ['order' => 1],
        );
    }
}
