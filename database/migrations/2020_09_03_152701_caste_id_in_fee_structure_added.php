<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CasteIdInFeeStructureAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('fees', 'admission_category_id')) {
            Schema::table('fees', function (Blueprint $table) {
                $table->dropForeign('fees_admission_category_id_foreign');
                $table->dropColumn('admission_category_id');
            });
        }
        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedBigInteger('caste_id')->after("status");
            // $table->foreign('caste_id')->references('id')->on('castes')->onDelete('cascade');
            $table->index('caste_id');
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
            $table->dropColumn('caste_id');
        });
    }
}
