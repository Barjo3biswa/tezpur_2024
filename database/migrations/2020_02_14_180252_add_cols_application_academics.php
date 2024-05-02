<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsApplicationAcademics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->string('academic_10_board')->nullable();
            $table->string('academic_10_year')->nullable();
            $table->string('academic_10_grade')->nullable();
            $table->string('academic_10_subject')->nullable();
            $table->string('academic_10_cgpa')->nullable();
            $table->string('academic_10_percentage')->nullable();
            $table->string('academic_10_remarks')->nullable();
            $table->string('academic_12_board')->nullable();
            $table->string('academic_12_year')->nullable();
            $table->string('academic_12_grade')->nullable();
            $table->string('academic_12_subject')->nullable();
            $table->string('academic_12_cgpa')->nullable();
            $table->string('academic_12_percentage')->nullable();
            $table->string('academic_12_remarks')->nullable();
           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            //
        });
    }
}
