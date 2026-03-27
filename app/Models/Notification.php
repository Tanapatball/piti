<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'product_id',
        'user_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')
              ->orWhere('user_id', $userId);
        });
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * สร้างการแจ้งเตือนสต็อกต่ำ
     */
    public static function createStockLowAlert(Product $product): self
    {
        return self::create([
            'type' => 'stock_low',
            'title' => 'สต็อกต่ำกว่ากำหนด',
            'message' => "สินค้า {$product->name} (รหัส: {$product->product_id}) มีจำนวนคงเหลือ {$product->current_stock} ต่ำกว่าขั้นต่ำที่กำหนด ({$product->stock_min})",
            'icon' => 'arrow-down',
            'color' => 'red',
            'link' => route('products.show', $product->product_id),
            'product_id' => $product->product_id,
        ]);
    }

    /**
     * สร้างการแจ้งเตือนสต็อกเกิน
     */
    public static function createStockOverAlert(Product $product): self
    {
        return self::create([
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
