<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');          // status_change, quantity_increase, quantity_decrease, note_update
            $table->text('description');       // human-readable log
            $table->json('meta')->nullable();  // extra data (old/new values)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
