<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // MC-000001
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('governorate');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', [
                'new',        // جديد
                'confirmed',  // مؤكد
                'preparing',  // قيد التجهيز
                'shipped',    // تم الشحن
                'delivered',  // تم التسليم
                'cancelled',  // ملغي
                'returned',   // مرتجع
            ])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
