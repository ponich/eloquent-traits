<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eloquent_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_class', 120)->index();
            $table->integer('model_id')->index();
            $table->string('type', 120);
            $table->string('key', 60);
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eloquent_attributes');
    }
}