<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order');
            $table->string('phone');
            $table->text('message');
            $table->boolean('picked_up');
            $table->dateTimeTz('created_at')
                ->nullable()
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTimeTz('updated_at')
                ->nullable();
            $table->integer('owner_id')
                ->nullable()
                ->unsigned()
                ->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
