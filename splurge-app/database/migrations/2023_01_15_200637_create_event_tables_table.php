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
        Schema::create('event_tables', function (Blueprint $table) {
            $table->id();
            
            $table->timestamps();
            
            $table->bigInteger('customer_event_id')->index();

            $table->string('name', 60);

            $table->smallInteger('capacity');

            $table->index(['name', 'customer_event_id'], 'idx_customer_event_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_tables');
    }
};
