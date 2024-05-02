<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubPrefCombinationCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('sub_preference')->default(0)->after('preference')->comment("1= true, 0 = false");
            $table->integer('sub_combination_id')->unsigned()->nullable()->after('sub_preference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(["sub_preference","sub_combination_id"]);
        });
    }
}
