<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_plants', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');  // Foreign key to the users table
            $table->unsignedBigInteger('plant_id');  // Foreign key to the plants table
            $table->primary(['user_id', 'plant_id']);  // Composite primary key
            $table->timestamps();  // Laravel's created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_plants');
    }
}
