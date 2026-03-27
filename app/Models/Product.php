<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ProductObserver;

#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = false; // ไม่ auto increment
    protected $keyType = 'string'; // ถ้ารหัสเป็น string เช่น P001, P002

    protected $fillable = [
        'product_id',
        'name',
        'category_id',
        'stock_min',
        'stock_max',
        'current_stock',
        'size',
        'pack',
        'weight_per_kg',
        'weight_total',
    ];

    // สินค้าอยู่ในหมวดหมู่ไหน
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // สินค้ามีธุรกรรมหลายรายการ (เช่น เบิก, รับเข้า)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_id', 'product_id');
    }

    // รายการรับเข้าของสินค้า
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'product_id', 'product_id');
    }

    // รายการเบิกออกของสินค้า
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'product_id', 'product_id');
    }
}
