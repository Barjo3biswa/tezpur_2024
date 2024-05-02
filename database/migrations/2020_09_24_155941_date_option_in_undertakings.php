<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateOptionInUndertakings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_list_undertakings', function (Blueprint $table) {
            $table->dateTime('closing_date_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_list_undertakings', function (Blueprint $table) {
            $table->dropColumn('closing_date_time');
        });
    }
}
