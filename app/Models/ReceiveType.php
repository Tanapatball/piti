<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveType extends Model
{
    use HasFactory;

    protected $primaryKey = 'receive_type_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['receive_type_id', 'name'];

    // ธุรกรรมหลายรายการที่ใช้ประเภทนี้
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'receive_type_id');
    }
}
