<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovCaseLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gov_case_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gov_case_id')->nullable();
            $table->integer('case_status_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('sender_user_role_id')->nullable();
            $table->integer('receiver_user_role_id')->nullable();
            $table->text('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gov_case_log');
    }
}
