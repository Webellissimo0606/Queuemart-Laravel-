<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable()->comemnt("user id return from facebook or google");
            $table->string('password')->nullable();
            $table->string('phone_number',20)->nullable();
            $table->string('code')->nullable();
            $table->boolean('activated')->default(0);
            $table->integer('role_id')->nullable();
            $table->string('user_image')->nullable();
            $table->string('nationality')->nullable();
            $table->string('ic')->nullable();
            $table->boolean('complete_profile')->nullable();
            $table->string('credits_rm')->default(0);
            $table->string('credits_sgd')->default(0);
            $table->boolean('reminder_whatsapp')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
