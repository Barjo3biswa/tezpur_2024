<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEligibilityQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eligibility_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('course_id');
            $table->string('type', 100)->default("text");
            $table->string('question');
            $table->json('question_details', 100);
            $table->softDeletes();
            $table->timestamps();
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eligibility_questions');
    }
}
