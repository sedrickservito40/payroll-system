<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Dtr;
use Carbon\Carbon;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $employees = collect();

        $cutoffStart = session('cutoff_start')
            ? Carbon::parse(session('cutoff_start'))
            : now();

        $cutoffEnd = $this->getCutoffEnd($cutoffStart);

        if ($request->filled('employee_number')) {

            $employees = Employee::where('employee_number', $request->employee_number)->get();

            foreach ($employees as $emp) {

                $emp->rate_per_day = ($emp->employee_rate * 12) / 314;
                $emp->basic_pay = ($emp->employee_rate / 2);

                //contributions
                $emp->pagibig = 100;

                $emp->abs_late_ut = $this->computeAbsLateUt(
                    $emp->employee_number,
                    $cutoffStart,
                    $cutoffEnd
                );

                // 💰 convert hours to money deduction
                $emp->abs_late_ut_ded = $emp->abs_late_ut * $emp->rate_per_day;

                $emp->gross_pay = $emp->basic_pay - $emp->abs_late_ut_ded;

                $emp->deductions = $emp->pagibig;

                $emp->net_pay = $emp->gross_pay - $emp->deductions;
            }
        }

        return view('payslip.index', compact(
            'employees',
            'cutoffStart',
            'cutoffEnd'
        ));
    }

    // =========================
    // ABS + LATE + UNDERTIME
    // =========================
    private function computeAbsLateUt($employeeNumber, $start, $end)
        {
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);

            $total = 0;

            $expectedIn = Carbon::createFromTime(8, 0, 0);
            $expectedOut = Carbon::createFromTime(17, 0, 0);

            foreach ($start->copy()->daysUntil($end) as $day) {

                // 🚫 SKIP WEEKENDS (Saturday + Sunday)
                if ($day->isWeekend()) {
                    continue;
                }

                $dtr = Dtr::where('employee_number', $employeeNumber)
                    ->whereDate('date', $day)
                    ->first();

                // ABSENT (only weekdays)
                if (!$dtr) {
                    $total += 1;
                    continue;
                }

                $timeIn = Carbon::parse($dtr->time_in);
                $timeOut = Carbon::parse($dtr->time_out);

                /*
                // LATE
                if ($timeIn->gt($expectedIn)) {
                    $total += $timeIn->diffInMinutes($expectedIn) / 60;
                }

                // UNDERTIME
                if ($timeOut->lt($expectedOut)) {
                    $total += $expectedOut->diffInMinutes($timeOut) / 60;
                }
                */
            }

            return $total;
        }

    private function getCutoffEnd($start)
    {
        $day = $start->day;

        if ($day == 10) {
            return $start->copy()->addDays(14);
        }

        if ($day == 25) {
            return $start->copy()->addMonth()->setDay(9);
        }

        if ($day >= 10 && $day <= 24) {
            return $start->copy()->setDay(24);
        }

        if ($day >= 25 || $day <= 9) {
            return $start->copy()->addMonth()->setDay(9);
        }

        return $start;
    }
}