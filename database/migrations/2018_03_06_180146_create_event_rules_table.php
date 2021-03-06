<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('search_value');
            $table->string('stop');
            $table->string('schedule_id');
            $table->string('object_name');
            $table->string('transport_type');
            $table->time('departure_at');
            $table->string('weekday');
            $table->time('notification_at');
            $table->integer('offset');
            $table->string('icon_url');
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamps();
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
        Schema::dropIfExists('event_rules');
    }
}
