<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhdQualifiyingMark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->string('qualified_national_level_test_mark', 100)->nullable()->after("qualified_national_level_test");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            //
        });
    }
}
