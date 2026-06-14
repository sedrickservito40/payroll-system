<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dtr extends Model
{
    protected $table = 'dtr';

    protected $fillable = [
        'employee_number',
        'date',
        'time_in',
        'time_out',
        'cutoff',
        'overtime',
        'ot_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'employee_number');
    }
}