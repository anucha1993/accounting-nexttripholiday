<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['notification_sa', 'notification_sale', 'notification_acc'];
        foreach ($tables as $table) {
            Schema::create($table, function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('message');
                $table->string('type');
                $table->string('notify_for');
                $table->string('reference_id')->nullable();
                $table->boolean('is_read')->default(false);
                if ($table->getTable() === 'notification_sale') {
                    $table->unsignedBigInteger('sale_id')->nullable();
                } else {
                    $table->unsignedBigInteger('user_id')->nullable();
                }
                $table->string('url')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['notification_sa', 'notification_sale', 'notification_acc'];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
