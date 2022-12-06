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
        Schema::create('customer_event_guests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('customer_event_id')->index();
            $table->string('name');
            $table->string('gender', 20)->nullable();
            $table->jsonb('accepted')->nullable();
            $table->jsonb('presented')->nullable();
            $table->dateTime('attendance_at')->nullable();
            $table->string('table_name', 120)->nullable();
            $table->string('barcode_image_url')->nullable();
            $table->string('tag')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_event_guests');
    }
};
