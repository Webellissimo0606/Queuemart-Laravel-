<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('branch_image')->nullable();
            $table->string('branch_name')->nullable();            
            $table->string('branch_label')->nullable();            
            $table->string('longitude')->nullable();            
            $table->string('latitude')->nullable();            
            $table->string('branch_tel_num')->nullable(); 
            $table->longText('branch_des')->nullable();
            $table->longText('branch_address')->nullable();
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
        Schema::dropIfExists('branch_models');
    }
}
