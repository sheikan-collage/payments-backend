<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'name', 'currency', 'description'];

    public function students()
    {
        return $this->hasMany(Student::class, 'fees_id');
    }
}
