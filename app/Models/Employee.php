<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'middle_name',
        'department',
        'school',
        'educational_attainment',
        'degree',
        'birthdate',
        'birthplace',
        'shift_in_sched',
        'shift_out_sched',
        'sss_number',
        'philhealth_number',
        'tin_number',
        'pagibig_number',
        'emp_img',
        'employee_level',
        'employee_rate',
    ];
}