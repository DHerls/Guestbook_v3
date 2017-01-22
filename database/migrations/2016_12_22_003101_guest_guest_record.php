<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestGuestRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_guest_record', function (Blueprint $table) {
            $table->integer('guest_record_id')->unsigned();
            $table->integer('guest_id')->unsigned();

            $table->timestamps();

            $table->foreign('guest_record_id')->references('id')->on('guest_records');
            $table->foreign('guest_id')->references('id')->on('guests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_guest_record');
    }
}
