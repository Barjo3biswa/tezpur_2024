<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListIdOnReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_receipts', function (Blueprint $table) {
            $table->bigInteger('merit_list_id')->nullable()->after("course_id");
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
            $table->dropColumn('merit_list_id');
        });
    }
}
