<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    static $TABLES  = ['media_owners', 'galleries', 'posts', 'services'];

    static $COLUMN_NAME = 'image_options';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (static::$TABLES as  $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->jsonb(static::$COLUMN_NAME)->nullable();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (static::$TABLES as  $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn(static::$COLUMN_NAME);
            });
        }
    }
};
