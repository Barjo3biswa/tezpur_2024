<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OtherQualifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_qualifications', function (Blueprint $table) {
            $table->string('total_mark', 100)->nullable()->after('subjects_taken');
            $table->string('mark_obtained', 100)->nullable()->after('total_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_qualifications', function (Blueprint $table) {
            $table->dropColumn('total_mark','mark_obtained');
        });
    }
}
