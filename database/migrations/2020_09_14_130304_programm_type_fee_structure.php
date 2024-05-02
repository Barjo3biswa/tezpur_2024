<?php

use App\Models\MeritList;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProgrammTypeFeeStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->string('programm_type', 100)->nullable()->default('not_required')->after("hostel_required")->comment(implode(",", MeritList::$programm_types));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('programm_type');
        });
    }
}
