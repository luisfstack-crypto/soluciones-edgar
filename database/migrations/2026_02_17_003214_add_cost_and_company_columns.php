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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('account_type')->default('personal'); // personal, company
            $table->boolean('is_verified_company')->default(false);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->default(0)->after('price'); 
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('service_cost_snapshot', 10, 2)->default(0)->after('price_at_purchase');
            $table->decimal('service_price_snapshot', 10, 2)->default(0)->after('service_cost_snapshot'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'phone', 'account_type', 'is_verified_company']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('cost');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['service_cost_snapshot', 'service_price_snapshot']);
        });
    }
};
