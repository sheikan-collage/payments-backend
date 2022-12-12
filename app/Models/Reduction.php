<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reduction extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'amount', 'is_percentage'];

    protected $casts = [
        'is_percentage' =>  'boolean'
    ];
}
