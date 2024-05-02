<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppliactionIsCritariaFullfilled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('is_eligibility_critaria_fullfilled')->default(false);
            $table->boolean('is_eligibility_critaria_submitted')->default(false);
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->boolean('is_eligibility_critaria_fullfilled')->default(false);
            $table->boolean('is_eligibility_critaria_submitted')->default(false);
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
            $table->dropColumn('is_eligibility_critaria_fullfilled');
            $table->dropColumn('is_eligibility_critaria_submitted');
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('is_eligibility_critaria_submitted');
            $table->dropColumn('is_eligibility_critaria_fullfilled');
        });
    }
}
