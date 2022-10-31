<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovCaseBibadiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gov_case_bibadi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gov_case_id')->nullable();
            $table->integer('ministry_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('is_main_bibadi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gov_case_bibadi');
    }
}
