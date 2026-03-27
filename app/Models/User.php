<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = ['fname', 'lname', 'email', 'phone', 'password', 'role_id', 'receive_stock_alert'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'receive_stock_alert' => 'boolean',
        ];
    }

    // ผู้ใช้เป็นของ Role ไหน
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdmin(): bool
    {
        return $this->role->role_name === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role->role_name === 'staff';
    }

    public function isUser(): bool
    {
        return $this->role->role_name === 'user';
    }

    // ผู้ใช้มีรายการธุรกรรม (transactions) ของตัวเอง
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
