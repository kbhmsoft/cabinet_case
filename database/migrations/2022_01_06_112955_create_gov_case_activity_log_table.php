<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovCaseActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gov_case_activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->json('user_info')->nullable()->comment("user_info,ip address and user_agent");
            $table->integer('gov_case_id')->nullable();
            $table->enum('activity_type', ['create','update','delete','view','generate','archive'])->nullable();
            $table->string('massage')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gov_case_activity_log');
    }
}
