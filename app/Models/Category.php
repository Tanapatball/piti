<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $incrementing = false; // ไม่ auto increment
    protected $keyType = 'string'; // หรือ 'int'

    protected $fillable = [
        'category_id',
        'category_name',
    ];

    // หมวดหมู่มีหลายสินค้า
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
