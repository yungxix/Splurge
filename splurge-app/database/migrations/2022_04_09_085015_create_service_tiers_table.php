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
        Schema::create('service_tiers', function (Blueprint $table) {
            $table->id();
            
            $table->timestamps();
            
            $table->integer("service_id")->unsigned()->index();

            $table->string("name", 200);

            $table->string("code", 15)->unique();

            $table->mediumText("description");

            $table->float("price")->nullable();

            $table->jsonb("options");


            $table->string("footer_message")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_tiers');
    }
};
