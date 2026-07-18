<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size')->nullable();          // مثال: S / M / L أو 52 / 54
            $table->string('color')->nullable();         // مثال: أسود / بيج
            $table->string('color_hex', 20)->nullable(); // لعرض دائرة اللون
            $table->unsignedInteger('price');            // سعر هذا الفاريانت
            $table->unsignedInteger('original_price')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
