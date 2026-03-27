<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Categories สำหรับสินค้า OEM ตามข้อมูล DBF
        DB::table('categories')->insert([
            ['category_id' => 'OEM',   'category_name' => 'สินค้า OEM',           'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 'CHO',   'category_name' => 'ชิ้นส่วน CHO',         'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 'BT',    'category_name' => 'ชิ้นส่วน BT',          'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 'VN',    'category_name' => 'ชิ้นส่วน VN',          'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 'OTHER', 'category_name' => 'อื่นๆ',               'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
