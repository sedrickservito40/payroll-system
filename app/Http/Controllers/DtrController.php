<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class DtrController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['dtrs' => function ($query) {
            $query->orderBy('date', 'desc');
        }])->get();

        return view('dtr.index', compact('employees'));
    }
}