<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $table = 'stock_outs';
    protected $primaryKey = 'id'; // <-- ตรงนี้ต้องเป็น id
    protected $keyType = 'int';  

    protected $fillable = [
        'product_id',
        'issue_type_id',
        'code',
        'trans_id',
        'reference_doc',
        'reference_no',
        'quantity',
        'fraction_qty',
        'user_id',
        'issued_date',
        'note'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function issueType()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id', 'issue_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // <-- ระบุ foreign key และ owner key
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trans_id', 'trans_id');
    }

}
