<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'title',
        'file_size',
        'file_length',
        'url',
        'transcription',
        'slug'

    ];
}
