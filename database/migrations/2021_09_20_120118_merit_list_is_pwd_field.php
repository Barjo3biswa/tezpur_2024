<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeritListIsPwdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->boolean('is_pwd')->default(false)->after("admission_category_id")->comment("true for PWD, false non-pwd candidate.");
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
            $table->dropColumn('is_pwd');
        });
    }
}
