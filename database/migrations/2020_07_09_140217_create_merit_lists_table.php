<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeritListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merit_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merit_master_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->integer('admission_category_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->string('application_no');
            $table->string('gender')->nullable();
            $table->integer('tuee_rank')->nullable();
            $table->boolean('status')->default(0)->comment('0=default,1=selected,2=admitted,3=cancelled');
            $table->dateTime('valid_till')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('merit_master_id')->references('id')->on('merit_masters')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admission_category_id')->references('id')->on('admission_categories')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merit_lists');
    }
}
