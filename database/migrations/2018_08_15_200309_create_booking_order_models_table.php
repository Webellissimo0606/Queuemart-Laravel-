<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingOrderModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_order_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('service_id');
            $table->integer('package_id');
            $table->integer('booking_status');
            $table->boolean('arrived_check')->nullable();
            $table->string('qrcode_field')->nullable();
            $table->integer('manager_id');
            $table->date("booking_day");
            $table->time("booking_time");
            $table->dateTime("booked_time")->nullable();
            $table->dateTime("paid_time")->nullable();
            $table->longText("paid_price")->nullable();
            $table->dateTime("arrived_time")->nullable();
            $table->dateTime("call_time")->nullable();
            $table->dateTime("complete_time")->nullable();
            $table->integer("order_duration")->nullable();
            $table->longText("client_notice")->nullable();
            $table->longText("admin_notice")->nullable();
            $table->integer("promo_id")->default(0);
            $table->longText("promo_code")->nullable();
            $table->boolean("rating_check")->default(0);
            $table->boolean("reschedule_check")->default(0);
            $table->dateTime("reschedule_time")->nullable();
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
        Schema::dropIfExists('booking_order_models');
    }
}
