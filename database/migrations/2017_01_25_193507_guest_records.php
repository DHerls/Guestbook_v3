<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('override_user_id')->unsigned()->nullable();
            $table->smallInteger('price');
            $table->enum('payment_method',['account','cash','pass']);
            $table->string('member_signature', 80);
            $table->string('guest_signature', 80);
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('override_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_records');
    }
}
