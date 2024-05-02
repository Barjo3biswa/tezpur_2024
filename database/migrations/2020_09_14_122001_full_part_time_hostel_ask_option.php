<?php

use App\Models\MeritList;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FullPartTimeHostelAskOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merit_lists', function (Blueprint $table) {
            $table->string('programm_type', 100)->nullable()->default('not_required')->after("valid_till")->comment(implode(",", MeritList::$programm_types));
            $table->boolean('ask_hostel')->default(false)->after("hostel_required");
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
            $table->dropColumn(["programm_type", "ask_hostel"]);
        });
    }
}
