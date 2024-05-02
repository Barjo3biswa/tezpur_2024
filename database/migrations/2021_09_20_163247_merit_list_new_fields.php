<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_masters', function (Blueprint $table) {
            $table->dateTime('initial_opening_date')->nullable()->after("name");
            $table->unsignedInteger('closing_after_days')->default(0)->after("initial_opening_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_masters', function (Blueprint $table) {
            $table->dropColumn('initial_opening_date','closing_after_days');
        });
    }
}
