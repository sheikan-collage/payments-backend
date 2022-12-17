<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'university_id',
        'level',
        'department',
        'batch',
        'is_active',
        'fees_id',
        'installments_id',
        'reductions_id',
    ];

    protected $casts = [
        'is_active' =>  'boolean'
    ];

    public function fees()
    {
        return $this->belongsTo(Fee::class, 'fees_id');
    }

    public function installments()
    {
        return $this->belongsTo(Installment::class, 'installments_id');
    }

    public function reductions()
    {
        return $this->belongsTo(Reduction::class, 'reductions_id');
    }
}
