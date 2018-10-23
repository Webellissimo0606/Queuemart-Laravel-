<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('company_url')->nullable();
            $table->string('company_image')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_tel_num')->nullable();
            $table->longText('company_des')->nullable();
            $table->boolean('permission_status')->default(0);
            $table->integer('support_id')->default(0);
            $table->string('questionnaire_link')->nullable();
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
        Schema::dropIfExists('company_models');
    }
}
