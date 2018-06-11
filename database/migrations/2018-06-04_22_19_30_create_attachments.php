<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eloquent_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_class', 120)->index();
            $table->integer('model_id')->index();
            $table->string('md5', 32)->index();
            $table->string('disk')->nullable();
            $table->string('path');
            $table->string('name');
            $table->string('type')->nullable();
            $table->integer('size')->default(0);
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
        Schema::dropIfExists('eloquent_attachments');
    }
}
