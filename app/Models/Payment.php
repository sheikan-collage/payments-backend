<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'university_id',
        'level',
        'department',
        'batch',
        'fees_data',
        'installments_data',
        'reductions_data',
        'payed_amount',
        'currency',
        'reference_id',
        'follow_up_number',
    ];

    protected $casts = [
        'fees_data' => 'json',
        'installments_data' => 'json',
        'reductions_data' => 'json',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
