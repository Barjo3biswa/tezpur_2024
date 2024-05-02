<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListProcessTechnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_masters', function (Blueprint $table) {
            $table->string('processing_technique', 100)->nullable()->after("session_year");
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
            $table->dropColumn('processing_technique');
        });
    }
}
