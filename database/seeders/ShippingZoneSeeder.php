<?php

namespace Admin\ShippingCharges\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingZoneSeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Seed the shipping zones
       DB::table('shipping_zones')->insert([
           ['name' => 'North America', 'created_at' => now(), 'updated_at' => now()],
           ['name' => 'Europe', 'created_at' => now(), 'updated_at' => now()],
           ['name' => 'Asia', 'created_at' => now(), 'updated_at' => now()],
       ]);
    }
}
