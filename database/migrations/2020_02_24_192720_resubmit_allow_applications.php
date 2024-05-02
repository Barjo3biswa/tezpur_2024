<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResubmitAllowApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->tinyInteger('resubmit_allow')->after("payment_status")->default(0)->comment("1= Allow, 0=Not allow");
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->tinyInteger('resubmit_allow')->after("payment_status")->default(0)->comment("1= Allow, 0=Not allow");
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
            $table->dropColumn('resubmit_allow');
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('resubmit_allow');
        });
    }
}
