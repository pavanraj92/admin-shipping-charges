<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingZoneAndRelatedTables extends Migration
{
    public function up(): void
    {
        // 1. Shipping Zones
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable()->comment('Shipping zone, e.g., "Asia", "North America"');
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });

        // 2. Shipping Methods
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable()->comment('Shipping method, e.g., "Standard Shipping", "Express Shipping"');
            $table->string('carrier',100)->nullable()->comment('Carrier name, e.g., "DHL", "FedEx"');
            $table->string('delivery_time',100)->nullable()->comment('Estimated delivery time, e.g., "3-5 days"');
            $table->decimal('base_rate', 10, 2)->nullable()->default(0.00);
            $table->foreignId('zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });

        // 3. Shipping Rates
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('based_on', ['weight', 'price', 'quantity'])->nullable()->default('weight');
            $table->decimal('min_value', 10, 2)->nullable()->default(0.00);
            $table->decimal('max_value', 10, 2)->nullable()->default(0.00);
            $table->decimal('rate', 10, 2)->nullable()->default(0.00);
            $table->foreignId('method_id')->constrained('shipping_methods')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Pivot Table for Shipping Zone and Countries
        Schema::create('shipping_zone_country', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->string('country_code', 2)->nullable(); // ISO country code, e.g., IN, US
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zone_country');
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('shipping_zones');
    }
}
 