<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassportNoDrivingApplicationEditeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->string('passport_number')->nullable();
            $table->string('driving_license_equivalnet_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('passport_number');
            $table->dropColumn('driving_license_equivalnet_no');     
        });
    }
}
