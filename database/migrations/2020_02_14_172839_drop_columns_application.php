<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('permanent_ps');
            $table->dropColumn('correspondence_ps');
            $table->dropColumn('other_qualification');
            $table->dropColumn('academic_10_board');     
            $table->dropColumn('academic_10_year');     
            $table->dropColumn('academic_10_grade');     
            $table->dropColumn('academic_10_subject');   
            $table->dropColumn('academic_10_cgpa');     
            $table->dropColumn('academic_10_percentage'); 
            $table->dropColumn('academic_10_remarks');   
            $table->dropColumn('academic_12_board');     
            $table->dropColumn('academic_12_year');
            $table->dropColumn('academic_12_grade');
            $table->dropColumn('academic_12_subject');
            $table->dropColumn('academic_12_cgpa');
            $table->dropColumn('academic_12_percentage');
            $table->dropColumn('academic_12_remarks');
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
