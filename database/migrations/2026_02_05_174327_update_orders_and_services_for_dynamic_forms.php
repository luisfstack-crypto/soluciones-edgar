<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('form_schema')->nullable()->after('description');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('input_data')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('input_data')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('form_schema');
        });
    }
};
