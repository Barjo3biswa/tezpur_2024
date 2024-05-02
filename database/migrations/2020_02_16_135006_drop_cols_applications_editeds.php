<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColsApplicationsEditeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('academic_10_stream');     
            $table->dropColumn('academic_10_school');     
            $table->dropColumn('academic_10_mark');     
            $table->dropColumn('academic_12_stream');     
            $table->dropColumn('academic_12_school');     
            $table->dropColumn('academic_12_mark'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            //
        });
    }
}
