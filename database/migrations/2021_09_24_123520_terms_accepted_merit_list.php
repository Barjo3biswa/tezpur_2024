<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TermsAcceptedMeritList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->dateTime('reported_at')->nullable();
            $table->text('terms_and_conditions')->nullable();
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
            $table->dropColumn(
                "reported_at", "terms_and_conditions"
            );
        });
    }
}
