<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FreeRegFeeOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('is_free_reg')->default(false);
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->boolean('is_free_reg')->default(false);
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
            $table->dropColumn("is_free_reg");
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn("is_free_reg");
        });
    }
}
