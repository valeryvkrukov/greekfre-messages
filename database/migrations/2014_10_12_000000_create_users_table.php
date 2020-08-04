<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name', 128);
            $table->string('email', 128)->unique();
            $table->mediumText('default_message')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 128);
            $table->rememberToken();
            $table->string('twilio_phone', 64)->nullable();
            $table->string('twilio_sid', 128)->nullable();
            $table->string('twilio_token', 128)->nullable();
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
