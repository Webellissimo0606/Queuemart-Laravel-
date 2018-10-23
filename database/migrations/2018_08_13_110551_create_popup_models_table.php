<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popup_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->longText('question')->nullable();
            $table->longText('answer')->nullable();
            $table->longText('before_popup_title')->nullable();
            $table->longText('before_popup_des')->nullable();
            $table->string('noholdcountdown')->default(24);
            $table->longText('after_popup_title')->nullable();
            $table->longText('after_popup_des')->nullable();
            $table->string('holdinghours')->default(48);
            $table->longText('home_top_1_title')->nullable();
            $table->longText('home_top_1_des')->nullable();
            $table->longText('home_top_2_title')->nullable();
            $table->longText('home_top_2_des')->nullable();
            $table->longText('home_top_4_title')->nullable();
            $table->longText('home_top_4_des')->nullable();
            $table->string('daysb4appointment')->default(1);
            $table->longText('home_top_3_title')->nullable();
            $table->longText('home_top_3_des')->nullable();
            $table->boolean('sms_after_booking')->default(0);
            $table->boolean('sms_payment')->default(0);
            $table->boolean('sms_cancel_admin')->default(0);
            $table->boolean('sms_cancel_client')->default(0);
            $table->boolean('sms_near_appt')->default(0);
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
        Schema::dropIfExists('popup_models');
    }
}
