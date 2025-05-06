<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['step','percent']);          // รูปแบบ
            $table->decimal('min_profit', 12, 2)->default(0);  // กำไรขั้นต่ำ (รวม VAT แล้ว)
            $table->decimal('max_profit', 12, 2)->nullable();  // null = ไม่จำกัดบน
            $table->decimal('value', 12, 2);                   // บาท/คน หรือ %
            $table->enum('unit', ['baht','percent']);          // baht=บาท/คน, percent=ร้อยละ
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
