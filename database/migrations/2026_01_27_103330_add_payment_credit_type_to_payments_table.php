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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_credit_type')->nullable()->after('payment_credit_slip_number')->comment('ประเภทการรูดบัตร: charge_customer = ชาร์จลูกค้า, pro_swipe = โปร รูดบัตร');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_credit_type');
        });
    }
};
