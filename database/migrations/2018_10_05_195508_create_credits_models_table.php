<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id'); 
            $table->integer('user_id'); 
            $table->integer('other_id'); 
            $table->integer('branch_id'); 
            $table->integer('service_id'); 
            $table->integer('package_id'); 
            $table->string('credit_unit'); 
            $table->integer('credit_amount'); 
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
        Schema::dropIfExists('credits_models');
    }
}
