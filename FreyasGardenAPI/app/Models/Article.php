<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (plural form)
    protected $table = 'articles';

    // Define the fillable fields
    protected $fillable = [
        'title',
        'plant_id',
        'source',
    ];

    // Define relationships
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
