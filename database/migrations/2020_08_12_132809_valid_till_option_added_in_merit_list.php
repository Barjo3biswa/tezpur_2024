<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ValidTillOptionAddedInMeritList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('merit_lists', 'valid_till')){
            Schema::table('merit_lists', function (Blueprint $table) {
                $table->dateTime('valid_till')->nullable()->after("valid_from");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->dropColumn('valid_till');
        });
    }
}
