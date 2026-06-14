<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DtrController extends Controller
{
    public function index()
    {
        $cutoff = session('cutoff_start');

        $start = null;
        $end = null;

        if ($cutoff) {
            $date = Carbon::parse($cutoff);
            $day = $date->day;

            if ($date->day == 25) {
                $start = $date->copy();
                $end = $date->copy()->addMonth()->setDay(9);
            }

            if ($date->day == 10) {
                $start = $date->copy();
                $end = $date->copy()->setDay(24);
            }
        }

        $employees = Employee::with(['dtrs' => function ($query) use ($start, $end) {
            if ($start && $end) {
                $query->whereBetween('date', [
                    $start->toDateString(),
                    $end->toDateString()
                ]);
            }
        }])->get();

        return view('dtr.index', compact('employees', 'start', 'end'));
    }
}