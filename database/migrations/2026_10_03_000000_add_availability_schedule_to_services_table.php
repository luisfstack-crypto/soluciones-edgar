<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->boolean('has_schedule')->default(false);
            $table->json('schedule_days')->nullable();
            $table->time('schedule_start')->nullable();
            $table->time('schedule_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn([
                'has_schedule',
                'schedule_days',
                'schedule_start',
                'schedule_end',
            ]);
        });
    }
};
