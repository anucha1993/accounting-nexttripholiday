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
        Schema::table('quote_logs', function (Blueprint $table) {
            // สถานะการคืนเงินลูกค้า
            $table->string('customer_refund_status')->nullable()->after('wholesale_skip_status');
            $table->timestamp('customer_refund_updated_at')->nullable()->after('customer_refund_status');
            $table->string('customer_refund_created_by')->nullable()->after('customer_refund_updated_at');
            
            // สถานะการคืนเงินโฮลเซลล์
            $table->string('wholesale_refund_status')->nullable()->after('customer_refund_created_by');
            $table->timestamp('wholesale_refund_updated_at')->nullable()->after('wholesale_refund_status');
            $table->string('wholesale_refund_created_by')->nullable()->after('wholesale_refund_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_logs', function (Blueprint $table) {
            $table->dropColumn([
                'customer_refund_status',
                'customer_refund_updated_at', 
                'customer_refund_created_by',
                'wholesale_refund_status',
                'wholesale_refund_updated_at',
                'wholesale_refund_created_by'
            ]);
        });
    }
};
