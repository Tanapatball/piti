<?php

namespace App\Observers;

use App\Mail\StockAlertMail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProductObserver
{
    public function updated(Product $product): void
    {
        if (!$product->wasChanged('current_stock')) {
            return;
        }

        $oldStock = $product->getOriginal('current_stock');
        $newStock = $product->current_stock;

        $crossedLow = false;
        $crossedOver = false;

        // ตรวจจับ: เดิมปกติ → ตอนนี้ต่ำกว่า min
        if ($product->stock_min > 0) {
            $wasAboveMin = $oldStock >= $product->stock_min;
            $nowBelowMin = $newStock < $product->stock_min;
            $crossedLow = $wasAboveMin && $nowBelowMin;
        }

        // ตรวจจับ: เดิมปกติ → ตอนนี้เกิน max
        if ($product->stock_max > 0) {
            $wasBelowMax = $oldStock <= $product->stock_max;
            $nowAboveMax = $newStock > $product->stock_max;
            $crossedOver = $wasBelowMax && $nowAboveMax;
        }

        if (!$crossedLow && !$crossedOver) {
            return;
        }

        // สร้างการแจ้งเตือนบนเว็บ
        if ($crossedLow) {
            Notification::createStockLowAlert($product);
        }

        if ($crossedOver) {
            Notification::createStockOverAlert($product);
        }

        // ส่งอีเมล
        $recipients = User::where('receive_stock_alert', true)->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $lowStock = $crossedLow ? collect([$product]) : collect();
        $overStock = $crossedOver ? collect([$product]) : collect();

        foreach ($recipients as $user) {
            Mail::to($user->email)->send(new StockAlertMail($lowStock, $overStock));
        }
    }
}
