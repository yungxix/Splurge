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
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('service_id')->index();
            $table->float('price')->nullable();
            $table->string('name');
            $table->string('pricing_type')->default('fixed');
            $table->json('options')->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('required')->default(false);
            $table->string('category', 15)->default('default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_items');
    }
};
