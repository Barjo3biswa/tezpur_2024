<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcHolderName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('account_holder_name', 100)->nullable()->after("bank_ac_no");
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->string('account_holder_name', 100)->nullable()->after("bank_ac_no");
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
            $table->dropColumn('account_holder_name');
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('account_holder_name');
        });
    }
}
