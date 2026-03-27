<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'user_code'   => '030368',
                'fname'       => 'สมชาย',
                'lname'       => 'จันทร์ดี',
                'email'       => 'somchai@example.com',
                'phone'       => '0812345678',
                'password'    => Hash::make('password'),
                'role_id'     => 1, // admin
                'receive_stock_alert' => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_code'   => '1234',
                'fname'       => 'สมหญิง',
                'lname'       => 'แสงทอง',
                'email'       => 'somying@example.com',
                'phone'       => '0898765432',
                'password'    => Hash::make('password'),
                'role_id'     => 2, // staff
                'receive_stock_alert' => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_code'   => '040368',
                'fname'       => 'วิชัย',
                'lname'       => 'ใจดี',
                'email'       => 'wichai@example.com',
                'phone'       => '0856789012',
                'password'    => Hash::make('password'),
                'role_id'     => 2, // staff
                'receive_stock_alert' => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_code'   => '050368',
                'fname'       => 'มานะ',
                'lname'       => 'รักงาน',
                'email'       => 'mana@example.com',
                'phone'       => '0867891234',
                'password'    => Hash::make('password'),
                'role_id'     => 3, // user
                'receive_stock_alert' => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
