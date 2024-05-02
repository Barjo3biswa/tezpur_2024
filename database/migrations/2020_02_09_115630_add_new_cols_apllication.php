<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColsApllication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('academic_10_grade')->after('academic_10_mark')->nullable();
            $table->string('academic_10_cgpa')->after('academic_10_grade')->nullable();
            $table->string('academic_10_remarks')->after('academic_10_percentage')->nullable();
            $table->string('academic_12_grade')->after('academic_12_mark')->nullable();
            $table->string('academic_12_cgpa')->after('academic_12_grade')->nullable();
            $table->string('academic_12_remarks')->after('academic_12_percentage')->nullable();
           

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
}
