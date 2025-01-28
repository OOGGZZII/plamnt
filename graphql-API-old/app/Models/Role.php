<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows the Laravel convention)
    protected $table = 'roles';

    // Mass-assignable attributes
    protected $fillable = [
        'role_name',
    ];

    /**
     * Define a relationship with the User model (Role has many Users)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
