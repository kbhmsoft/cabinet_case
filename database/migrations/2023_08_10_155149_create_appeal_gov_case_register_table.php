<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppealGovCaseRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appeal_gov_case_register', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_no')->nullable();
            $table->tinyInteger('case_category_id')->nullable();
            $table->tinyInteger('case_type_id')->nullable();
            $table->string('year')->nullable();
            $table->tinyInteger('appeal_office_id')->nullable();
            $table->tinyInteger('concern_new_appeal_person_designation')->nullable();
            $table->tinyInteger('concern_user_id')->nullable();
            $table->text('postpond_date')->nullable();
            $table->text('postponed_details')->nullable();
            $table->integer('case_number_origin')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appeal_gov_case_register');
    }
}
