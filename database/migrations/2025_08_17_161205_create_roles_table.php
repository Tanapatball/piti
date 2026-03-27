<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id'); // Primary Key
            $table->string('role_name', 50)->unique(); // ชื่อ role (เช่น admin, staff, user)
            $table->string('description', 255)->nullable(); // คำอธิบายเพิ่มเติม
            $table->timestamps(); // created_at, updated_at
        });

        // เพิ่ม role พื้นฐาน
        DB::table('roles')->insert([
            ['role_name' => 'admin', 'description' => 'ผู้ดูแลระบบ', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'staff', 'description' => 'พนักงาน', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'user', 'description' => 'ผู้ใช้งานทั่วไป', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
