<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCurrentStock extends Command
{
    protected $signature = 'stock:sync';
    protected $description = 'Sync current_stock ของสินค้าทั้งหมดให้ตรงกับยอดรับ-เบิกจริง (received - issued)';

    public function handle()
    {
        $this->info('กำลัง sync current_stock (สูตร: รับเข้า - เบิกออก)...');

        $products = Product::all();
        $count = 0;

        // คำนวณยอดรับเข้าทั้งหมดแยกตาม product_id
        $received = DB::table('transaction_items')
            ->select('product_id')
            ->selectRaw('SUM(full_qty) as total_received')
            ->groupBy('product_id')
            ->pluck('total_received', 'product_id');

        // คำนวณยอดเบิกออกทั้งหมดแยกตาม product_id
        $issued = DB::table('stock_outs')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_issued')
            ->groupBy('product_id')
            ->pluck('total_issued', 'product_id');

        foreach ($products as $product) {
            $recv = $received->get($product->product_id, 0);
            $iss = $issued->get($product->product_id, 0);
            $newStock = $recv - $iss;

            // อัปเดตถ้าค่าต่างกัน
            if ($product->current_stock != $newStock) {
                $oldStock = $product->current_stock;
                $product->current_stock = $newStock;
                $product->save();

                $this->line("  [{$product->product_id}] {$product->name}: {$oldStock} → {$newStock} (รับ:{$recv} - เบิก:{$iss})");
                $count++;
            }
        }

        if ($count > 0) {
            $this->info("อัปเดต {$count} รายการสำเร็จ!");
        } else {
            $this->info('ไม่มีรายการที่ต้องอัปเดต (ข้อมูลตรงกันแล้ว)');
        }

        return Command::SUCCESS;
    }
}
