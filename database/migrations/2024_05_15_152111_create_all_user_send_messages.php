<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllUserSendMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_user_send_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('messages')->nullable();
            $table->integer('user_sender')->nullable();
            $table->integer('user_type')->nullable();
            $table->tinyInteger('receiver_seen')->default('0');
            $table->string('seen_at')->nullable();
            $table->tinyInteger('msg_reqest')->default('0');
            $table->tinyInteger('msg_remove')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_user_send_messages');
    }
}
