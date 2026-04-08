<?php

namespace App\Console\Commands;

use App\Mail\StockAlertMail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendStockAlert extends Command
{
    protected $signature = 'stock:alert {--to= : Override recipient email}';
    protected $description = 'ส่งอีเมลแจ้งเตือนเมื่อสต็อกสินค้าต่ำกว่า Min หรือเกิน Max';

    public function handle(): int
    {
        $lowStock = Product::whereColumn('current_stock', '<', 'stock_min')
            ->where('stock_min', '>', 0)->get();

        $overStock = Product::whereColumn('current_stock', '>', 'stock_max')
            ->where('stock_max', '>', 0)->get();

        if ($lowStock->isEmpty() && $overStock->isEmpty()) {
            $this->info('ไม่มีสินค้าที่ต้องแจ้งเตือน');
            return self::SUCCESS;
        }

        // สร้าง Notification ในระบบ (แจ้งเตือนที่ระฆัง)
        $this->createNotifications($lowStock, $overStock);

        // ถ้าระบุ --to ส่งไปที่เดียว (manual send จาก Dashboard)
        if ($this->option('to')) {
            Mail::to($this->option('to'))->send(new StockAlertMail($lowStock, $overStock));
            $this->info("ส่งอีเมลแจ้งเตือนไปที่ {$this->option('to')} สำเร็จ");
            $this->info("- สต็อกต่ำ: {$lowStock->count()} รายการ | สต็อกเกิน: {$overStock->count()} รายการ");
            return self::SUCCESS;
        }

        // ส่งให้ users ที่ subscribe ไว้
        $recipients = User::where('receive_stock_alert', true)->get();

        if ($recipients->isEmpty()) {
            $this->info('ไม่มีผู้รับแจ้งเตือน (กรุณาตั้งค่าในหน้า ตั้งค่าแจ้งเตือน)');
            return self::SUCCESS;
        }

        foreach ($recipients as $user) {
            Mail::to($user->email)->send(new StockAlertMail($lowStock, $overStock));
        }

        $names = $recipients->pluck('email')->implode(', ');
        $this->info("ส่งอีเมลแจ้งเตือนให้ {$recipients->count()} คน สำเร็จ ({$names})");
        $this->info("- สต็อกต่ำ: {$lowStock->count()} รายการ | สต็อกเกิน: {$overStock->count()} รายการ");

        return self::SUCCESS;
    }

    /**
     * สร้าง Notification ในระบบสำหรับสินค้าที่สต็อกผิดปกติ
     */
    protected function createNotifications($lowStock, $overStock): void
    {
        // สร้าง Notification สำหรับสินค้าสต็อกต่ำ
        foreach ($lowStock as $product) {
            // ตรวจสอบว่ามี notification สำหรับสินค้านี้ที่ยังไม่ได้อ่านอยู่หรือไม่
            $exists = Notification::where('product_id', $product->product_id)
                ->where('type', 'stock_low')
                ->whereNull('read_at')
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$exists) {
                Notification::create([
                    'type' => 'stock_low',
                    'title' => 'สต็อกต่ำกว่ากำหนด',
                    'message' => "สินค้า {$product->name} (รหัส: {$product->product_id}) มีจำนวนคงเหลือ {$product->current_stock} ต่ำกว่าขั้นต่ำที่กำหนด ({$product->stock_min})",
                    'icon' => 'arrow-down',
                    'color' => 'red',
                    'link' => route('products.show', $product->product_id),
                    'product_id' => $product->product_id,
                ]);
            }
        }

        // สร้าง Notification สำหรับสินค้าสต็อกเกิน
        foreach ($overStock as $product) {
            $exists = Notification::where('product_id', $product->product_id)
                ->where('type', 'stock_over')
                ->whereNull('read_at')
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$exists) {
                Notification::create([
                    'type' => 'stock_over',
                    'title' => 'สต็อกเกินกำหนด',
                    'message' => "สินค้า {$product->name} (รหัส: {$product->product_id}) มีจำนวนคงเหลือ {$product->current_stock} เกินสูงสุดที่กำหนด ({$product->stock_max})",
                    'icon' => 'arrow-up',
                    'color' => 'yellow',
                    'link' => route('products.show', $product->product_id),
                    'product_id' => $product->product_id,
                ]);
            }
        }
    }
}
