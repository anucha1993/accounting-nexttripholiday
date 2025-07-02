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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('revised')->default(false)->after('invoice_image')->comment('สถานะการแก้ไขเอกสาร');
            $table->text('revision_reason')->nullable()->after('revised')->comment('เหตุผลในการแก้ไข');
            $table->timestamp('revision_date')->nullable()->after('revision_reason')->comment('วันที่แก้ไข');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['revised', 'revision_reason', 'revision_date']);
        });
    }
};
