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
        Schema::create('table_customer', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_textid');
            $table->string('customer_tel');
            $table->string('customer_fax');
            $table->string('customer_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_customer');
    }
};
