<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeclinedOtpFieldInMeritList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->string('declined_otp')->nullable();
            $table->dateTime('declined_at')->nullable();
            $table->text('declined_text')->nullable();
            $table->text('declined_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->dropColumn('declined_otp', 'declined_at', 'declined_text', 'declined_remark');
        });
    }
}
