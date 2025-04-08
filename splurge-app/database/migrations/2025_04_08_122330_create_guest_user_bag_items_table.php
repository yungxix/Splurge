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
        Schema::create('guest_user_bag_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->foreignId('event_user_id')
                ->constrained('splurge_event_users')->cascadeOnDelete();
            $table->string('item_type', strlen('sourvenier') + 2);
            $table->dateTime('confirmed_at')->nullable();
            $table->string('confirmed_by', 30)->nullable();     
            $table->smallInteger('item_count')->default(1);
            $table->foreignId('remote_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_user_bag_items');
    }
};
