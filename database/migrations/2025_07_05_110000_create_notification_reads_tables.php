<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // สำหรับ NotificationSA
        Schema::create('notification_sa_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['notification_id', 'user_id']);
        });
        // สำหรับ NotificationAcc (ถ้าต้องการใช้ร่วมกันแบบเดียวกัน)
        Schema::create('notification_acc_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['notification_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_sa_reads');
        Schema::dropIfExists('notification_acc_reads');
    }
};
