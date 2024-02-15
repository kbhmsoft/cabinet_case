<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationFormAsMainDefendentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_form_as_main_defendents', function (Blueprint $table) {
            $table->id();
            $table->string('case_category')         ->nullable();
            $table->string('case_category_type')    ->nullable();
            $table->string('case_no')               ->nullable();
            $table->text('main_defendant_comments') ->nullable();
            $table->text('additional_comments')     ->nullable();
            $table->string('main_defendant_pdf')    ->comment('PDF file');
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
        Schema::dropIfExists('application_form_as_main_defendents');
    }
}
