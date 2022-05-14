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
        Schema::create('splurge_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->mediumInteger("user_id")->index();
            $table->string("token")->uniqid();
            $table->morphs("access");
            $table->dateTime("expires_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('splurge_access_tokens');
    }
};
