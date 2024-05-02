<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *'application_id','student_id','is_btech','course_id','preference'
     * @return void
     */
    public function up()
    {
        Schema::create('applied_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id')->nullable();
            $table->string('student_id')->nullable();
            $table->boolean('is_btech')->default(false);
            $table->string('course_id')->nullable();
            $table->integer('preference')->nullable();
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
        Schema::dropIfExists('applied_courses');
    }
}
