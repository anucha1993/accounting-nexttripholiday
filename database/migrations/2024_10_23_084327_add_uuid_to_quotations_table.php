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
        Schema::table('quotation', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id'); // เพิ่มฟิลด์ UUID ที่เป็นคีย์ไม่ซ้ำ
        });
    }
    
    public function down()
    {
        Schema::table('quotation', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
    
};
