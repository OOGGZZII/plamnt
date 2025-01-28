<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlant extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (plural form)
    protected $table = 'user_plants';

    // Define the primary key if it's not the default `id`
    protected $primaryKey = ['user_id', 'plant_id'];

    // Disable incrementing for composite keys
    public $incrementing = false;

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }
}
