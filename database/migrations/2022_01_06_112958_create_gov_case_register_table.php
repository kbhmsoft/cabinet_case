<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovCaseRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gov_case_register', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_no')->nullable();
            $table->date('date_issuing_rule_nishi')->nullable();
            $table->tinyInteger('action_user_id')->nullable();
            $table->string('arji_file')->nullable();
            $table->tinyInteger('result')->nullable();
            $table->string('result_file')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('comments')->nullable();
            $table->tinyInteger('in_favour_govt')->nullable();
            $table->integer('create_by')->nullable();
            $table->text('govt_lost_reason')->nullable();
            $table->integer('gov_case_ref_id')->nullable();
            $table->string('ref_gov_case_no')->nullable();
            $table->integer('case_status_id')->nullable();
            $table->integer('case_division_id')->nullable();
            $table->integer('case_category_id')->nullable();
            $table->integer('year')->nullable();
            $table->integer('concern_user_id')->nullable();
            $table->text('subject_matter')->nullable();
            $table->text('postponed_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gov_case_register');
    }
}
