<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // ใบรับสินค้า ตาม format run_no จาก DBF: OEM-DDMMYY/NNN
        DB::table('transactions')->insert([
            [
                'trans_id'        => 'OEM-040768/001',
                'trans_date'      => '2025-07-04',
                'reference_doc'   => null,
                'reference_no'    => null,
                'receive_type_id' => 1, // รับสินค้าปกติ (code 00)
                'note'            => 'เศษลูกค้า OEM นับสต๊อก 100%',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'trans_id'        => 'OEM-040768/002',
                'trans_date'      => '2025-07-04',
                'reference_doc'   => null,
                'reference_no'    => null,
                'receive_type_id' => 1,
                'note'            => 'รับสินค้า OEM ล็อตที่ 2',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'trans_id'        => 'OEM-100768/001',
                'trans_date'      => '2025-07-10',
                'reference_doc'   => null,
                'reference_no'    => null,
                'receive_type_id' => 1,
                'note'            => 'รับสินค้า OEM จากซัพพลายเออร์',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'trans_id'        => 'OEM-150768/001',
                'trans_date'      => '2025-07-15',
                'reference_doc'   => null,
                'reference_no'    => null,
                'receive_type_id' => 2, // รับคืนจากลูกค้า
                'note'            => 'รับคืนสินค้าจากลูกค้า',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'trans_id'        => 'OEM-200768/001',
                'trans_date'      => '2025-07-20',
                'reference_doc'   => null,
                'reference_no'    => null,
                'receive_type_id' => 1,
                'note'            => 'รับสินค้า OEM ประจำสัปดาห์',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);

        // รายการสินค้าในแต่ละใบรับ (code จาก DBF: 237150521141, 219250404094)
        DB::table('transaction_items')->insert([
            // OEM-040768/001
            ['trans_id' => 'OEM-040768/001', 'product_id' => 'OEM-CHOVNAA1017180',   'item_code' => '237150521141', 'full_qty' => 100, 'fraction_qty' => 0, 'net_weight' => 50.00, 'created_at' => now(), 'updated_at' => now()],
            ['trans_id' => 'OEM-040768/001', 'product_id' => 'OEM-CHOVNAA10181100',  'item_code' => '237150521142', 'full_qty' => 200, 'fraction_qty' => 0, 'net_weight' => 150.00, 'created_at' => now(), 'updated_at' => now()],

            // OEM-040768/002
            ['trans_id' => 'OEM-040768/002', 'product_id' => 'OEM-CHOBTAA107003140', 'item_code' => '219250404094', 'full_qty' => 50, 'fraction_qty' => 0, 'net_weight' => 15.00, 'created_at' => now(), 'updated_at' => now()],
            ['trans_id' => 'OEM-040768/002', 'product_id' => 'OEM-CHOBTAA107002125', 'item_code' => '219250404095', 'full_qty' => 75, 'fraction_qty' => 0, 'net_weight' => 18.75, 'created_at' => now(), 'updated_at' => now()],

            // OEM-100768/001
            ['trans_id' => 'OEM-100768/001', 'product_id' => 'OEM-CHOVNBB2025100',   'item_code' => '237150610001', 'full_qty' => 150, 'fraction_qty' => 0, 'net_weight' => 60.00, 'created_at' => now(), 'updated_at' => now()],
            ['trans_id' => 'OEM-100768/001', 'product_id' => 'OEM-CHOVNBB2030150',   'item_code' => '237150610002', 'full_qty' => 100, 'fraction_qty' => 0, 'net_weight' => 55.00, 'created_at' => now(), 'updated_at' => now()],

            // OEM-150768/001
            ['trans_id' => 'OEM-150768/001', 'product_id' => 'OEM-CHOBTCC305080',    'item_code' => '219250715001', 'full_qty' => 30, 'fraction_qty' => 0, 'net_weight' => 18.00, 'created_at' => now(), 'updated_at' => now()],

            // OEM-200768/001
            ['trans_id' => 'OEM-200768/001', 'product_id' => 'OEM-GENERAL001',       'item_code' => '237150720001', 'full_qty' => 500, 'fraction_qty' => 0, 'net_weight' => 100.00, 'created_at' => now(), 'updated_at' => now()],
            ['trans_id' => 'OEM-200768/001', 'product_id' => 'OEM-CHOVNAA1017180',   'item_code' => '237150720002', 'full_qty' => 80, 'fraction_qty' => 0, 'net_weight' => 40.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
