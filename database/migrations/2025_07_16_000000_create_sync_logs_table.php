<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->timestamp('synced_at');
            $table->integer('total_synced')->default(0);
            $table->integer('total_updated')->default(0);
            $table->integer('total_inserted')->default(0);
            $table->string('status')->default('success');
            $table->text('error_message')->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('sync_logs');
    }
};
