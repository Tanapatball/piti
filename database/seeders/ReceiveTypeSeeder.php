<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceiveTypeSeeder extends Seeder
{
    public function run(): void
    {
        // it_code จาก DBF: "00" = รับสินค้าปกติ
        DB::table('receive_types')->insert([
            ['code' => '00', 'name' => 'รับสินค้าปกติ',       'created_at' => now(), 'updated_at' => now()],
            ['code' => '01', 'name' => 'รับคืนจากลูกค้า',     'created_at' => now(), 'updated_at' => now()],
            ['code' => '02', 'name' => 'โอนย้ายคลัง',        'created_at' => now(), 'updated_at' => now()],
            ['code' => '03', 'name' => 'รับบริจาค',          'created_at' => now(), 'updated_at' => now()],
            ['code' => '04', 'name' => 'รับจากการผลิต',      'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
