<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (plural form)
    protected $table = 'posts';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'city',
        'title',
        'plant',
        'description',
        'media',
        'sell',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant');
    }
}
