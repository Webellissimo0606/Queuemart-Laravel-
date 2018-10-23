<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('package_name')->nullable();
            $table->string('package_des')->nullable();
            $table->string('package_unit')->default('RM');
            $table->string('package_price')->nullable();
            $table->string('package_participants')->nullable();
            $table->integer('service_id');
            $table->integer('credit_amount')->default(0);
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
        Schema::dropIfExists('package_models');
    }
}
