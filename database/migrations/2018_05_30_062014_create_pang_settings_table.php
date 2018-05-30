<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePangSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pang_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->time("morning_early")->default("08:00:00");
            $table->time("morning_start")->default("09:00:00");
            $table->time("morning_late")->default("10:00:00");
            $table->time("morning_end")->default("13:30:00");
            $table->time("afternoon_start")->default("14:30:00");
            $table->time("afternoon_leave")->default("16:30:00");
            $table->time("afternoon_extra")->default("19:00:00");
            $table->time("afternoon_end")->default("20:00:00");
            $table->double("earning_pang")->default(0.3);
            $table->double("losing_pang")->default(0.5);
            $table->integer("absent_loss")->default(50);
            $table->integer("current_promo_id")->default(1999);
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
        Schema::dropIfExists('pang_settings');
    }
}
