<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('price');
            $table->enum('payment_method',['account','cash','pass']);
            $table->binary('member_signature')->nullable();
            $table->binary('guest_signature')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('user_id')->references('id')->on('users');
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
