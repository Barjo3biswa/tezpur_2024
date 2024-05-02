<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_qualifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('exam_name')->nullable();
            $table->string('board_name')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('class_grade')->nullable();
            $table->string('subjects_taken')->nullable();
            $table->string('cgpa')->nullable();
            $table->string('marks_percentage')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('other_qualifications');
    }
}
