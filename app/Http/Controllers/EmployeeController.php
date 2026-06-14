<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(5);
        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|unique:employees',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $data = $request->only([
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
                'employee_level',
                'employee_rate',
                'employee_status',
            ]);

        // IMAGE handling (blob version)
        if ($request->hasFile('emp_img')) {
            $data['emp_img'] = file_get_contents($request->file('emp_img'));
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function update(Request $request, Employee $employee)
        {
            $employee->update($request->all());

            return redirect()->route('employees.index');
        }
}