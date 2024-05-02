<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentAssignedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_assigned_users', function (Blueprint $table) {
            $table->bigInteger('department_user_id');
            $table->bigInteger('department_id');
            $table->index(["department_user_id", 'department_id'], "dept_assigned");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_assigned_users');
    }
}
