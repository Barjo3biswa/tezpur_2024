<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationEligibilityCritariaAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_eligibility_critaria_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('question_id');
            $table->string('question');
            $table->string('total');
            $table->string('answer');
            $table->string('operator_condition');
            $table->string('eligibility_requirement');
            $table->boolean('is_eligibility_pass');
            $table->softDeletes();
            $table->timestamps();
            $table->index('application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_eligibility_critaria_answers');
    }
}
