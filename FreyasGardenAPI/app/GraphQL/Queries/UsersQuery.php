<?php
namespace App\GraphQL\Queries;

use App\Models\User;

class UsersQuery {
    public function resolve() {
        return User::all();
    }
}