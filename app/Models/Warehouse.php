<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $primaryKey = 'warehouse_id';
    protected $fillable = ['warehouse_name', 'location'];

    // คลังสินค้ามีหลายสินค้า
    public function products()
    {
        return $this->hasMany(Product::class, 'warehouse_id');
    }
}
