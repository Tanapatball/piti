<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // current_stock is now included in the products table migration directly
    }

    public function down(): void
    {
        //
    }
};
