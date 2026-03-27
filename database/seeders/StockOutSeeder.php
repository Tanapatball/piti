<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockOutSeeder extends Seeder
{
    public function run(): void
    {
        // เบิกสินค้า ตาม format run_no จาก DBF: OEM-120768/001
        // ot_code จาก DBF: "04", "08", "11" = ประเภทการเบิก
        DB::table('stock_outs')->insert([
            [
                'product_id'   => 'OEM-CHOVNAA1017180',
                'trans_id'     => 'OEM-040768/001',
                'quantity'     => 10,
                'fraction_qty' => 0,
                'user_id'      => 1, // user_code: 030368
                'issued_to'    => 'แผนกผลิต A',
                'issued_date'  => '2025-07-12',
                'note'         => 'ใบเบิกสินค้า',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_id'   => 'OEM-CHOBTAA107003140',
                'trans_id'     => 'OEM-040768/002',
                'quantity'     => 5,
                'fraction_qty' => 2,
                'user_id'      => 1,
                'issued_to'    => 'แผนกผลิต B',
                'issued_date'  => '2025-07-14',
                'note'         => 'ใบเบิกสินค้า',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_id'   => 'OEM-CHOBTAA107002125',
                'trans_id'     => 'OEM-040768/002',
                'quantity'     => 8,
                'fraction_qty' => 3,
                'user_id'      => 1,
                'issued_to'    => 'แผนกผลิต B',
                'issued_date'  => '2025-07-14',
                'note'         => 'ใบเบิกสินค้า',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_id'   => 'OEM-CHOVNBB2025100',
                'trans_id'     => 'OEM-100768/001',
                'quantity'     => 20,
                'fraction_qty' => 0,
                'user_id'      => 2, // user_code: 1234
                'issued_to'    => 'แผนก PC',
                'issued_date'  => '2025-07-17',
                'note'         => 'ใบเบิกสินค้า-PC',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_id'   => 'OEM-GENERAL001',
                'trans_id'     => 'OEM-200768/001',
                'quantity'     => 50,
                'fraction_qty' => 0,
                'user_id'      => 1,
                'issued_to'    => 'แผนกผลิต A',
                'issued_date'  => '2025-07-22',
                'note'         => 'เบิกสินค้าทั่วไป',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // ลด stock ใน transaction_items ตามที่เบิกออก (FIFO)
        DB::table('transaction_items')
            ->where('trans_id', 'OEM-040768/001')
            ->where('product_id', 'OEM-CHOVNAA1017180')
            ->update(['full_qty' => 90]); // 100 - 10

        DB::table('transaction_items')
            ->where('trans_id', 'OEM-040768/002')
            ->where('product_id', 'OEM-CHOBTAA107003140')
            ->update(['full_qty' => 45]); // 50 - 5

        DB::table('transaction_items')
            ->where('trans_id', 'OEM-040768/002')
            ->where('product_id', 'OEM-CHOBTAA107002125')
            ->update(['full_qty' => 67]); // 75 - 8

        DB::table('transaction_items')
            ->where('trans_id', 'OEM-100768/001')
            ->where('product_id', 'OEM-CHOVNBB2025100')
            ->update(['full_qty' => 130]); // 150 - 20

        DB::table('transaction_items')
            ->where('trans_id', 'OEM-200768/001')
            ->where('product_id', 'OEM-GENERAL001')
            ->update(['full_qty' => 450]); // 500 - 50
    }
}
