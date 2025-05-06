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
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('current_type', ['step','percent'])->default('step');
            $table->timestamps();
        });

        // ใส่ค่า default แถวแรก
        DB::table('commission_settings')->insert(['current_type' => 'step']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
