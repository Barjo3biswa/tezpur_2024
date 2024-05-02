<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraDocumentUpload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->tinyInteger('is_extra_doc_uploaded')->default(0)->comment("0 not, 1 yes");
            // $table->string('extra_doc_url', 255)->nullable();
            // $table->text('extra_doc_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(["is_extra_doc_uploaded"]);
        });
    }
}
