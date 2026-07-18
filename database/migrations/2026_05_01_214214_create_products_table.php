<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // اسم المنتج
            $table->string('description')->nullable();     // وصف قصير (تحت الاسم)
            $table->string('image');                       // مسار الصورة (assets/images/...)
            $table->unsignedInteger('price');              // السعر الحالي
            $table->unsignedInteger('original_price')->nullable(); // السعر قبل الخصم (اختياري)
            // discount_type: 'percentage' | 'fixed' | null
            // إذا original_price موجود والـ discount_type null سيُحسب تلقائياً
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 8, 2)->nullable(); // قيمة الخصم (نسبة أو مبلغ)
            $table->string('ribbon_label')->nullable();    // نص الـ ribbon (عرض خاص / الأكثر طلباً...)
            $table->boolean('is_featured')->default(false); // هل مميز (لون مختلف)
            $table->unsignedSmallInteger('sort_order')->default(0); // ترتيب العرض
            $table->boolean('is_active')->default(true);   // هل ظاهر في الصفحة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

