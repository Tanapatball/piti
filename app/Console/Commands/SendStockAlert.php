<?php

namespace App\Console\Commands;

use App\Mail\StockAlertMail;
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
}
