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
        Schema::create('splurge_event_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title', 30)->nullable();
            $table->string('gender', 12)->nullable();
            $table->string('customer_relationship', 30)->nullable();
            $table->string('role', 30)->default('guest');
            $table->string('tag', 30)->unique();
            $table->bigInteger('remote_id')->unsigned()->nullable();
            $table->foreignId('event_id')->constrained("splurge_events")->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('splurge_event_users');
    }
};
