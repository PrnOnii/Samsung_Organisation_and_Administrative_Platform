<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditPangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_pangs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("student_id");
            $table->foreign("student_id")->references("id")->on("students")->onDelete("cascade");
            $table->date("day");
            $table->double("quantity");
            $table->string("reason");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edit_pangs');
    }
}
