<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_academics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id')->nullable();
            $table->string('academic_graduation_board')->nullable();
            $table->string('academic_graduation_year')->nullable();
            $table->string('academic_graduation_grade')->nullable();
            $table->string('academic_graduation_subject')->nullable();
            $table->string('academic_graduation_cgpa')->nullable();
            $table->string('academic_graduation_percentage')->nullable();
            $table->string('academic_graduation_remarks')->nullable();
            $table->string('academic_post_graduation_board')->nullable();
            $table->string('academic_post_graduation_year')->nullable();
            $table->string('academic_post_graduation_grade')->nullable();
            $table->string('academic_post_graduation_subject')->nullable();
            $table->string('academic_post_graduation_cgpa')->nullable();
            $table->string('academic_post_graduation_percentage')->nullable();
            $table->string('academic_post_graduation_remarks')->nullable();
            $table->boolean('is_sport_represented')->default(false);
            $table->boolean('is_debarred')->default(false);
            $table->boolean('is_academic_prizes')->default(false);
            $table->boolean('is_punished')->default(false);
            $table->text('other_information')->nullable();
            $table->text('furnish_details')->nullable();
            $table->string('jee_roll_no')->nullable();                       
            $table->string('jee_form_no')->nullable();                       
            $table->string('jee_year')->nullable();                          
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
        Schema::dropIfExists('application_academics');
    }
}
