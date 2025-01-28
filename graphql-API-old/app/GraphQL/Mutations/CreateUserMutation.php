<?php
namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserMutation {
    public function resolve($root, array $args) {
        return User::create([
            'username' => $args['username'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'city' => $args['city'],
            'birthdate' => $args['birthdate'],
        ]);
    }
}