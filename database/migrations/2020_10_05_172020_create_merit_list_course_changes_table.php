<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeritListCourseChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merit_list_course_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('merit_list_id');
            $table->unsignedBigInteger('old_course_id');
            $table->unsignedBigInteger('new_course_id');
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
        Schema::dropIfExists('merit_list_course_changes');
    }
}
