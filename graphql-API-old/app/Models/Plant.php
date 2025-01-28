<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (plural form)
    protected $table = 'plants';

    // Define the fillable fields
    protected $fillable = [
        'name',
        'latin_name',
    ];

    // Define relationships
    public function posts()
    {
        return $this->hasMany(Post::class, 'plant');
    }

    public function userPlants()
    {
        return $this->belongsToMany(User::class, 'user_plants', 'plant_id', 'user_id');
    }
}
