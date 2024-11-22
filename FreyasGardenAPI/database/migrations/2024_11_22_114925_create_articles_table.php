<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->text('source');
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
