<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->string('middle_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->nullable()->after('middle_name');
            $table->string("isd_code", 5)->nullable()->after("roll_number");
            $table->dateTime("otp_expired_at")->nullable()->after("otp_verified_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name','name');
            $table->dropColumn(["middle_name", "last_name", "isd_code"]);
        });
    }
}
