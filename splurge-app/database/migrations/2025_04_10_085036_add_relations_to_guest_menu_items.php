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
        Schema::table('guest_menu_items', function (Blueprint $table) {
            $table->foreignId('menu_item_id');
            $table->foreignId('event_user_id')->constrained('splurge_event_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_menu_items', function (Blueprint $table) {
            $table->dropColumn(['menu_item_id', 'event_user_id']);
        });
    }
};
