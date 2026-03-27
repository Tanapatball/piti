<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_id',
        'product_id',
        'code',
        'item_code',
        'full_qty',
        'fraction_qty',
        'net_weight',
    ];

    // Relationship กับ Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Relationship กับ Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trans_id', 'trans_id');
    }

    // Accessor สำหรับ size จาก Product
    public function getSizeAttribute()
    {
        return $this->product->size ?? '-';
    }

    // Accessor สำหรับ pack จาก Product
    public function getPackAttribute()
    {
        return $this->product->pack ?? '-';
    }
}
