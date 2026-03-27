<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // คลังสินค้า OEM ตามข้อมูล DBF
        DB::table('warehouses')->insert([
            ['warehouse_name' => 'คลัง OEM หลัก',      'location' => 'อาคาร A โซน 1', 'created_at' => now(), 'updated_at' => now()],
            ['warehouse_name' => 'คลัง OEM สำรอง',     'location' => 'อาคาร A โซน 2', 'created_at' => now(), 'updated_at' => now()],
            ['warehouse_name' => 'คลังเศษลูกค้า',       'location' => 'อาคาร B โซน 1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
