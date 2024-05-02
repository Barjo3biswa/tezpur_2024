<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeApplicationTableBankInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            //
            $table->bigInteger('family_income')->nullable();
            $table->string('bank_ac_no');
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('ifsc_code');
            $table->string('bank_reg_mobile_no');
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
