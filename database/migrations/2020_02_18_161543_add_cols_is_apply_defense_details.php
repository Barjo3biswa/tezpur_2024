<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsIsApplyDefenseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->string('employment_details')->nullable();
            $table->string('km_details')->nullable();
            $table->string('minority_details')->nullable();
            $table->boolean('is_aplly_defense_quota')->default(false);
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
            //
        });
    }
}
