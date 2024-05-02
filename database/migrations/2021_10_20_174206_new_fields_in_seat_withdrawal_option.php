<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldsInSeatWithdrawalOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->date('dob')->nullable()->after("student_id");
            $table->string('bank_account', 200)->nullable()->after("dob");
            $table->string('holder_name', 200)->nullable()->after("bank_account");
            $table->string('bank_name', 200)->nullable()->after("holder_name");
            $table->string('branch_name', 200)->nullable()->after("bank_name");
            $table->string('ifsc_code', 100)->nullable()->after("branch_name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(["dob", "bank_account", "holder_name", "bank_name", "branch_name", "ifsc_code"]);
        });
    }
}
