<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColApplicEditeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn(["ncl_valid_upto","anm_or_lhv","anm_or_lhv_registration","academic_voc_stream","academic_voc_year","academic_voc_board","academic_voc_school","academic_voc_subject","academic_voc_mark","academic_voc_percentage","academic_anm_stream","academic_anm_year","academic_anm_board","academic_anm_school","academic_anm_subject","academic_anm_mark","academic_anm_percentage","bpl","english_mark_obtain"
            ]);
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
