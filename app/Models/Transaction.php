<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'trans_id';
    public $incrementing = false; // เพราะ trans_id เป็น varchar
    protected $keyType = 'string';

    protected $fillable = [
        'trans_id', 'trans_date', 'reference_doc', 'reference_no',
        'receive_type_id', 'note'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'trans_id', 'trans_id');
    }

    public function receiveType()
    {
        return $this->belongsTo(ReceiveType::class, 'receive_type_id', 'receive_type_id');
    }
}

