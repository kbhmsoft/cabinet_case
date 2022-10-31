<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovCaseHearingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gov_case_hearing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gov_case_id')->nullable();
            $table->date('hearing_date')->nullable();
            $table->string('hearing_file')->nullable();
            $table->text('comments')->nullable();
            $table->string('hearing_result_file')->nullable();
            $table->text('hearing_result_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gov_case_hearing');
    }
}
