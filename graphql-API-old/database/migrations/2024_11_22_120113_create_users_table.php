<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key (id)
            $table->string('username');  // User's username
            $table->string('email')->unique();  // User's email, unique for login
            $table->string('city');  // City where the user is located
            $table->date('birthdate');  // User's birthdate
            $table->string('password');  // Hashed password
            $table->foreignId('role_id')->constrained('roles');  // Foreign key reference to the roles table
            $table->boolean('active')->default(1);  // User account is active (1) or not (0)
            $table->timestamps();  // Laravel's created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
