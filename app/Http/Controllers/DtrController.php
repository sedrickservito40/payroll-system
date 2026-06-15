<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Dtr;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DtrController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $cutoff = session('cutoff_start');

        $start = null;
        $end = null;

        if ($cutoff) {
            $date = Carbon::parse($cutoff);

            if ($date->day == 25) {
                $start = $date->copy();
                $end = $date->copy()->addMonth()->setDay(9);
            }

            if ($date->day == 10) {
                $start = $date->copy();
                $end = $date->copy()->setDay(24);
            }
        }

        $employees = Employee::whereIn('employee_status', ['REG', 'PROBY'])
    ->with(['dtrs' => function ($query) use ($start, $end) {
        if ($start && $end) {
            $query->whereBetween('date', [
                $start->toDateString(),
                $end->toDateString()
            ]);
        }
    }])
    ->get()
    ->map(function ($employee) {
        $dtrsByDate = $employee->dtrs->keyBy(function ($dtr) {
            return Carbon::parse($dtr->date)->toDateString();
        });

        $employee->dtrsByDate = $dtrsByDate;

        foreach ($employee->dtrs as $dtr) {
            $this->computeOvertime($dtr, $employee);
        }

        return $employee;
    });

return view('dtr.index', compact('employees', 'start', 'end', 'search'));
        
    }

    private function computeOvertime($dtr, $employee)
    {
        if (!$dtr->time_in || !$dtr->time_out) {
            $dtr->early_ot = 0;
            $dtr->after_ot = 0;
            $dtr->raw_ot = 0;
            return;
        }

        $shiftIn = Carbon::parse($employee->shift_in_sched);
        $shiftOut = Carbon::parse($employee->shift_out_sched);

        $timeIn = Carbon::parse($dtr->time_in);
        $timeOut = Carbon::parse($dtr->time_out);

        // EARLY OT
        $earlyMinutes = $timeIn->lt($shiftIn)
            ? $timeIn->diffInMinutes($shiftIn)
            : 0;

        $earlyOt = ($earlyMinutes >= 30)
            ? round($earlyMinutes / 60, 2)
            : 0;

        // AFTER OT
        $afterMinutes = $timeOut->gt($shiftOut)
            ? $shiftOut->diffInMinutes($timeOut)
            : 0;

        $afterOt = ($afterMinutes >= 30)
            ? round($afterMinutes / 60, 2)
            : 0;

        $dtr->early_ot = $earlyOt;
        $dtr->after_ot = $afterOt;
        $dtr->raw_ot = $earlyOt + $afterOt;
    }

       public function update(Request $request)
        {
            $cutoff = session('cutoff_start');

            foreach ($request->rows as $key => $row) {

                if (is_numeric($key)) {

                    $dtr = Dtr::find($key);

                    if (!$dtr) continue;

                    $dtr->update([
                        'time_in'  => $row['time_in'] ?: null,
                        'time_out' => $row['time_out'] ?: null,
                        'overtime' => isset($row['overtime']) ? (float) $row['overtime'] : null,
                        'ot_type'  => $row['ot_type'] ?: null,
                        'cutoff'   => $cutoff, // 👈 ADD THIS
                    ]);

                } else {

                    $date = str_replace('new_', '', $key);

                    if (empty($row['time_in']) && empty($row['time_out'])) {
                        continue;
                    }

                    Dtr::updateOrCreate(
                        [
                            'employee_number' => $request->employee_number,
                            'date'            => $date,
                            'cutoff'          => $cutoff, // 👈 IMPORTANT KEY
                        ],
                        [
                            'time_in'  => $row['time_in'] ?: null,
                            'time_out' => $row['time_out'] ?: null,
                            'overtime' => isset($row['overtime']) ? (float) $row['overtime'] : null,
                            'ot_type'  => $row['ot_type'] ?: null,
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', 'DTR updated successfully');
        }
}