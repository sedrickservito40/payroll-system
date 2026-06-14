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

            $employees = Employee::whereIn('employee_status', ['REG', 'PROBY'])
                ->where('employee_number', $request->employee_number)
                ->get();

            foreach ($employees as $emp) {

                // =========================
                // RATE COMPUTATION
                // =========================
                $emp->rate_per_day = ($emp->employee_rate * 12) / 314;
                $emp->rate_per_hour = $emp->rate_per_day / 8;
                $emp->basic_pay = $emp->employee_rate / 2;

                // =========================
                // REGULAR OT
                // =========================
                $emp->regular_ot = $this->computeRegularOT(
                    $emp->employee_number,
                    $cutoffStart,
                    $cutoffEnd
                );

                $emp->regular_ot_pay =
                    $emp->regular_ot * $emp->rate_per_hour * 1.25;

                // =========================
                // CONTRIBUTIONS
                // =========================
                $emp->pagibig = 100;
                $emp->philhealth = 150;
                $emp->sss_employee = 0;
                $emp->sss_employer = 0;
                $emp->wth_tax = 0;

                // =========================
                // ATTENDANCE
                // =========================
                $attendance = $this->computeAbsLateUt(
                    $emp->employee_number,
                    $cutoffStart,
                    $cutoffEnd
                );

                $emp->absent_days = $attendance['absent'];
                $emp->late = $attendance['late'];
                $emp->ut = $attendance['undertime'];

                // =========================
                // COMBINED VALUE
                // =========================
                $emp->abs_late_ut =
                    $emp->absent_days +
                    $emp->late +
                    $emp->ut;

                // =========================
                // DEDUCTIONS (CLEAN RULE)
                // =========================
                $emp->abs_late_ut_ded =
                    ($emp->absent_days * $emp->rate_per_day) +
                    (($emp->late + $emp->ut) * $emp->rate_per_hour);

                // =========================
                // PAY COMPUTATION
                // =========================
                $emp->gross_pay =
                    $emp->basic_pay +
                    $emp->regular_ot_pay -
                    $emp->abs_late_ut_ded;

                $emp->total_deductions =
                    $emp->pagibig +
                    $emp->philhealth +
                    $emp->sss_employee +
                    $emp->wth_tax;

                $emp->net_pay =
                    $emp->gross_pay - $emp->total_deductions;
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

            $expectedIn = Carbon::createFromTime(8, 0, 0);
            $expectedOut = Carbon::createFromTime(17, 0, 0);

            $absentDays = 0;
            $lateHours = 0;
            $undertimeHours = 0;

            foreach ($start->copy()->daysUntil($end) as $day) {

                if ($day->isWeekend()) {
                    continue;
                }

                $dtr = Dtr::where('employee_number', $employeeNumber)
                    ->whereDate('date', $day)
                    ->first();

                // ======================
                // ABSENT
                // ======================
                if (!$dtr || !$dtr->time_in || !$dtr->time_out) {
                    $absentDays += 1;
                    continue;
                }

                $timeIn = Carbon::parse($dtr->time_in);
                $timeOut = Carbon::parse($dtr->time_out);

                // ======================
                // LATE (15-minute grace period cutoff)
                // ======================
                $graceLimit = $expectedIn->copy()->addMinutes(15); // 08:15

                if ($timeIn->gt($graceLimit)) {
                    $lateHours += $timeIn->diffInMinutes($expectedIn) / 60;
                }

                // ======================
                // UNDERTIME
                // ======================
                if ($timeOut->lt($expectedOut)) {
                    $undertimeHours += $expectedOut->diffInMinutes($timeOut) / 60;
                }
            }

            return [
                'absent' => $absentDays,
                'late' => round($lateHours, 2),
                'undertime' => round($undertimeHours, 2),
            ];
        }
    
        private function computeRegularOT($employeeNumber, $start, $end)
            {
                $start = Carbon::parse($start);
                $end = Carbon::parse($end);

                $totalOTHours = 0;

                $dtrs = Dtr::where('employee_number', $employeeNumber)
                    ->whereBetween('date', [$start, $end])
                    ->where('ot_type', 'REG')
                    ->get();

                foreach ($dtrs as $dtr) {

                    if (!$dtr->overtime) {
                        continue;
                    }

                    // assuming overtime is stored in HOURS already
                    $totalOTHours += (float) $dtr->overtime;
                }

                return $totalOTHours;
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