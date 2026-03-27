<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Console\Command;

class SyncCurrentStock extends Command
{
    protected $signature = 'stock:sync';
    protected $description = 'Sync current_stock ของสินค้าทั้งหมดให้ตรงกับยอดรับ-เบิกจริง';

    public function handle()
    {
        $this->info('กำลัง sync current_stock...');

        $products = Product::all();
        $count = 0;

        foreach ($products as $product) {
            // transaction_items.full_qty ถูกลดผ่าน FIFO แล้ว จึงเป็นยอดคงเหลือจริง
            $newStock = TransactionItem::where('product_id', $product->product_id)
                ->sum('full_qty');

            // อัปเดตถ้าค่าต่างกัน
            if ($product->current_stock != $newStock) {
                $oldStock = $product->current_stock;
                $product->current_stock = $newStock;
                $product->save();

                $this->line("  [{$product->product_id}] {$product->name}: {$oldStock} → {$newStock}");
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
