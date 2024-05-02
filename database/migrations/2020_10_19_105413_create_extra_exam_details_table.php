<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraExamDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_exam_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('student_id');
            $table->string('name_of_the_exam', 100);
            $table->date('date_of_exam', 100)->nullable();
            $table->string('registration_no', 100);
            $table->float('score_obtained', 10, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('extra_exam_details');
    }
}
