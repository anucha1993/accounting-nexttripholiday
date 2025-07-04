<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->string('related_type')->nullable(); // เช่น quotation, booking
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('status')->default('unread'); // read/unread
            $table->string('action_url')->nullable(); // URL สำหรับลิงก์ไปยังหน้าที่เกี่ยวข้อง
            $table->json('data')->nullable(); // เก็บข้อมูลเพิ่มเติมในรูปแบบ JSON
            $table->timestamps();
            
            // สร้าง index เพื่อเพิ่มประสิทธิภาพในการค้นหา
            $table->index(['user_id', 'status']);
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
