<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_user_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('notification_id');
            $table->string('group'); // admin, sale, accounting
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'notification_id', 'group']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_user_reads');
    }
};
