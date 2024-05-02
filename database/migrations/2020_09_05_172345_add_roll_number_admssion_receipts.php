<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRollNumberAdmssionReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->string('roll_number', 100)->nullable()->after("receipt_no");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->dropColumn('roll_number');
        });
    }
}
