<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortenUrl extends Model
{
    use HasFactory;
    protected $table = 'shorten_urls';
    protected $primaryKey = 'id';
    protected $fillable = [
        'url',
        'hash'
    ];
}
