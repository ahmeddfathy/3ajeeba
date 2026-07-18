<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        DB::statement('ALTER TABLE products MODIFY price INT UNSIGNED NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        DB::statement('UPDATE products SET price = 0 WHERE price IS NULL');
        DB::statement('ALTER TABLE products MODIFY price INT UNSIGNED NOT NULL');
    }
};
