<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->unsignedBigInteger('user_id');  // Foreign key to the users table
            $table->string('city');  // City for the post
            $table->string('title');  // Title of the post
            $table->unsignedBigInteger('plant');  // Foreign key to the plants table
            $table->text('description');  // Description of the post
            $table->unsignedBigInteger('media')->nullable();  // Media (optional)
            $table->boolean('sell');  // 0 = buy, 1 = sell
            $table->timestamps();  // Laravel's created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plant')->references('id')->on('plants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
