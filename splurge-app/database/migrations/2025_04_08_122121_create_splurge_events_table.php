<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('splurge_events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->date('event_date', 255);
            $table->string('description', 500);
            $table->boolean('require_confirmation_for_guests')->default(false);
            $table->integer('service_tier_id')->index();
            $table->string('code', 30)->unique();
            $table->string('status', 20)->default('PENDING');
            $table->bigInteger('remote_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('splurge_events');
    }
};
