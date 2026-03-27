<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
    use HasFactory;

    protected $primaryKey = 'issue_type_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['issue_type_id', 'name'];

    // รายการเบิกที่ใช้ประเภทนี้
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'issue_type_id');
    }
}
