<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldsTotalMarksOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_academics', function (Blueprint $table) {
            $table->decimal('physics_total_mark', 5, 2)->nullable()->default(0.00)->after("proposed_area_of_research");
            $table->string('physics_grade', 20)->nullable()->after("physics_total_mark");
            
            $table->decimal('chemistry_total_mark', 5, 2)->nullable()->default(0.00)->after("physics_mark");
            $table->string('chemistry_grade', 20)->nullable()->after("chemistry_total_mark");
            
            $table->decimal('mathematics_total_mark', 5, 2)->nullable()->default(0.00)->after("chemistry_mark");
            $table->string('mathematics_grade', 20)->nullable()->after("mathematics_total_mark");

            $table->decimal('english_total_mark', 5, 2)->nullable()->default(0.00)->after("mathematics_mark");
            $table->string('english_grade', 20)->nullable()->after("english_total_mark");

            $table->decimal('statistics_total_mark', 5, 2)->nullable()->default(0.00)->after("english_mark");
            $table->string('statistics_grade', 20)->nullable()->after("statistics_total_mark");

            $table->decimal('biology_total_mark', 5, 2)->nullable()->default(0.00)->after("statistics_mark");
            $table->string('biology_grade', 20)->nullable()->after("biology_total_mark");

            $table->decimal('english_mark_10_total_mark', 5, 2)->nullable()->default(0.00)->after("biology_mark");
            $table->string('english_mark_10_grade', 20)->nullable()->after("english_mark_10_total_mark");
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
