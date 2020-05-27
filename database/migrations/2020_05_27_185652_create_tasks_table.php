<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTimeTz('complete_by')->nullable();
            $table->dateTimeTz('completed_at')->nullable();
            $table->bigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
