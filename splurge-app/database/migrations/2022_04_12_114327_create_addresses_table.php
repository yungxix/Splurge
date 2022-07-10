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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name")->nullable();
            $table->string("line1");
            $table->string("line2")->nullable();
            $table->morphs("addressable");
            $table->string("cateogory", 60)->nullable();
            $table->float("latitude")->nullable();
            $table->float("longitude")->nullable();
            $table->string("state", 30)->default("Lagos");
            $table->string("zip", 12)->nullable();
            $table->string("country", 4)->default("NG");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
