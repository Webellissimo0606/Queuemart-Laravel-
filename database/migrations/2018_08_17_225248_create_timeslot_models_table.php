<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeslotModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeslot_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("service_id");
            $table->integer("duration")->default(0);
            $table->integer("calendar")->default(1);
            $table->integer("arrived")->default(30);
            $table->integer("show_estimated")->default(1);
            $table->integer("reschedule_allow")->default(0);

            $week_days=[
                'sunday',
                'monday','tuesday',
                'wednesday','thursday',
                'friday','saturday',
            ];

            foreach ($week_days as $day){
                $table->boolean($day."_active")->default(0);
                $table->longText($day."_val")->nullable();
            }

            $table->boolean('duration_show')->default(0);
            $table->string('service_duration')->nullable();
            $table->time('start_time')->nullable();                        
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
        Schema::dropIfExists('timeslot_models');
    }
}
