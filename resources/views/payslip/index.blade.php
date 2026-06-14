<x-app-layout>

<div class="py-6">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">

        <!-- Search -->
        <form method="GET" class="mb-6 flex gap-2">
            <input type="text"
                   name="employee_number"
                   value="{{ request('employee_number') }}"
                   placeholder="Employee Number"
                   class="w-80 border-gray-300 rounded-lg">

            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Search
            </button>
        </form>

        <div class="bg-white w-full overflow-x-auto">

            <table class="w-full border-2 border-black table-fixed mt-2">

                <colgroup>
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[20%]">
                </colgroup>

                <tbody>

                @forelse($employees as $emp)

                <!-- ABS LATE UT SUMMARY ROW -->
                    <tr class="bg-yellow-50 font-semibold">
                        <td colspan="9" class="px-4 py-3 text-red-600">
                            ABS/LATE/UT:
                            {{($emp->abs_late_ut) }}
                        </td>
                    </tr>
                    <!-- Header -->
                    <tr class="bg-gray-50">
                        <td colspan="5" class="px-4 py-3">
                            Employee Number: {{ $emp->employee_number }}
                        </td>
                        <td colspan="4" class="px-4 py-3 text-left">
                            Period: {{ $cutoffStart->format('Y-m-d') }}
                            → {{ $cutoffEnd->format('Y-m-d') }}
                        </td>
                    </tr>

                   <!-- Info -->
                    <tr>
                        <td colspan="5" class="px-4 py-3 font-semibold text-left">
                           Name: {{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }}
                        </td>

                        <td colspan="4" class="px-4 py-3 text-gray-600 text-left">
                            Department: {{ $emp->department }}
                        </td>
                    </tr>

                    <!-- Rate -->
                    <tr>
                        <td colspan="9" class="px-4 py-3 text-green-600 font-bold">
                            Rate: ₱{{ number_format($emp->employee_rate, 2) }}  / {{ number_format($emp->rate_per_day, 2) }}
                        </td>
                    </tr>

                    <!-- Section -->
                    <tr class="bg-gray-100 font-semibold">
                        <td colspan="3" class="px-4 py-2 text-center border-r border-gray-300">Earnings</td>
                        <td colspan="6" class="px-4 py-2 text-center">Deductions</td>
                    </tr>

                    <!-- Column labels -->
                    <tr class="bg-gray-50 text-sm font-semibold">
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2">Hours</td>
                        <td class="px-3 py-2 border-r border-gray-300">Amount</td>
                       <td class="px-3 py-2">
                            Contribution
                        </td>

                        <td class="px-3 py-2 border-gray-300">
                            Amount
                        </td>

                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Co. Deduction</td>
                        <td class="px-3 py-2">Amount</td>
                        <td class="px-3 py-2">Balance</td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2">
                            Basic Pay:
                        </td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300">
                            {{ number_format($emp->basic_pay, 2) }}
                        </td>
                        <td class="px-3 py-2">SSS: </td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Cash Advance</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Reg OT</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Pagibig</td>
                        <td class="px-3 py-2">{{ number_format($emp->pagibig, 2) }}</td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Donation</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Sun OT</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">PhilHealth</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Others</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">RD OT</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Withholding Tax</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Spl Hol OT</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Leg Hol OT</td>

                        <td class="px-3 py-2"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>
                    </tr>

                     <tr>
                        <td class="px-3 py-2">Night Diff</td>

                        <td class="px-3 py-2"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300">Gov. Loans</td>

                        <td class="px-3 py-2 border-b border-gray-300">Amount</td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300">Balance</td>

                        <td class="px-3 py-2 border-b border-gray-300">Other loans</td>

                        <td class="px-3 py-2 border-b border-gray-300">Amount</td>

                        <td class="px-3 py-2 border-b border-gray-300">Balance</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">SL/VL</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">SSS Loan</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Loan</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Abs/Late/UT</td>
                        <td></td>
                        <td class="px-3 py-2 border-r border-gray-300">
                            {{ $emp->abs_late_ut_ded == 0 ? '-' : number_format($emp->abs_late_ut_ded, 2) }}
                        </td>
                        <td class="px-3 py-2">Multi-purpose</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2 border-r border-gray-300"></td>
                        <td class="px-3 py-2">Rice</td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>

                     <tr>
                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300">Others</td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300">Others</td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 border-b border-gray-300">Gross Pay</td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300">{{ number_format($emp->gross_pay, 2) }}</td>

                        <td class="px-3 py-2 border-b border-gray-300">Deductions</td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-r border-gray-300 border-b border-gray-300">
                            {{ $emp->deductions == 0 ? '-' : number_format($emp->deductions, 2) }}
                        </td>

                        <td class="px-3 py-2 border-b border-gray-300">Netpay</td>

                        <td class="px-3 py-2 border-b border-gray-300"></td>

                        <td class="px-3 py-2 border-b border-gray-300">{{ number_format($emp->net_pay, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            @if(request('employee_number'))
                                No employee found
                            @else
                                Type employee number to search
                            @endif
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>
</div>

</x-app-layout>