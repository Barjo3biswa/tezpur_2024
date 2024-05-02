<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_seats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admission_category_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('total_seats');
            $table->integer('total_seats_applied');
            $table->boolean('status')->default(1);
            $table->string('session_year')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('course_seats');
    }
}
