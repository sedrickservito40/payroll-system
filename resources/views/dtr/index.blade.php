<x-app-layout>
        <div
            class="py-2"
            x-data="{
                open: false,
                selected: '{{ $search }}'
            }"
            x-on:close-modal.window="open = false"
        >

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Upload Button --}}
   
            <button
                type="button"
                @click="open = true"
                class="fixed right-6 top-1/2 -translate-y-1/2 bg-green-600 text-white p-3 rounded-full shadow-lg z-50 hover:bg-green-700"
            >
               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 16V4m0 0l-4 4m4-4l4 4" />
                </svg>
            </button>

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                {{-- LEFT: EMPLOYEES --}}
                <div class="md:col-span-3 bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold mb-3">Employees</h2>

                    <ul class="space-y-2">
                        @foreach($employees as $employee)
                            <li
                                    class="border p-2 rounded cursor-pointer hover:bg-gray-100"
                                    @click="window.location.href='{{ route('dtr.index') }}?search={{ $employee->employee_number }}'"
                                >
                                <div class="font-bold">
                                    {{ $employee->employee_number }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- RIGHT: DTR RECORDS --}}
                <div class="md:col-span-9 bg-white p-4 shadow rounded">
                    <div class="flex justify-between items-center mb-3">

                    <h2 class="text-lg font-semibold">DTR Records</h2>

                    @if($start && $end)
                        <div class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded">
                            Cutoff:
                            <strong>{{ $start->toDateString() }} - {{ $end->toDateString() }}</strong>
                        </div>
                    @elseif(session('cutoff_start'))
                        <div class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded">
                            Active:
                            <strong>{{ session('cutoff_start') }}</strong>
                        </div>
                    @endif

                </div>

                    <template x-if="!selected">
                        <p class="text-gray-500">Select an employee to view DTR</p>
                    </template>

                    @foreach($employees as $employee)
                        <div x-show="selected === '{{ $employee->employee_number }}'" x-cloak>

                            {{-- EMPLOYEE HEADER --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">

                                <div class="flex justify-between items-start">

                                    <div>
                                        <div class="text-sm text-gray-500">Employee No.</div>

                                        <div class="text-lg font-bold text-blue-700">
                                            {{ $employee->employee_number }} -
                                            {{ $employee->first_name }}
                                            {{ $employee->middle_name }}
                                            {{ $employee->last_name }}
                                        </div>

                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $employee->department }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Shift Schedule</div>

                                        <div class="text-sm font-semibold text-gray-700">
                                            {{ $employee->shift_in_sched }} - {{ $employee->shift_out_sched }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                           {{-- DTR TABLE WITH ABSENT LOGIC --}}
                            @php
                                $dates = collect();

                                if ($start && $end) {
                                    $period = \Carbon\CarbonPeriod::create($start, $end);

                                    foreach ($period as $date) {
                                        $dates->push($date->toDateString());
                                    }
                                }

                                $dtrsByDate = $employee->dtrs->keyBy('date');
                            @endphp

                            @if($dates->count())
                                <form method="POST" action="{{ route('dtr.update') }}">
                                    @csrf
                                    <div class="mt-3 text-right">
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-2">
                                            Apply Changes
                                        </button>
                                    </div>

                                    <table class="w-full border text-sm text-center">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th>Date</th>
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Raw OT</th>
                                                <th class="w-48">OT Type</th>
                                                <th class="w-24">Overtime</th>
                                            </tr>
                                        </thead>

                                    <tbody>
                                        @foreach($dates as $date)
                                            @php
                                                $dtr = $dtrsByDate[$date] ?? null;
                                                $isRestDay = \Carbon\Carbon::parse($date)->isWeekend();
                                            @endphp

                                            <tr>

                                            <input type="hidden" name="employee_number" value="{{ $employee->employee_number }}">
                                                <td>{{ $date }}</td>

                                                {{-- TIME IN --}}
                                                <td>
                                                    <input type="time"
                                                        name="rows[{{ $dtr->id ?? 'new_'.$date }}][time_in]"
                                                        class="border p-1 rounded w-full"
                                                        value="{{ $dtr->time_in ?? '' }}">
                                                </td>

                                                {{-- TIME OUT --}}
                                                <td>
                                                    <input type="time"
                                                        name="rows[{{ $dtr->id ?? 'new_'.$date }}][time_out]"
                                                        class="border p-1 rounded w-full"
                                                        value="{{ $dtr->time_out ?? '' }}">
                                                </td>

                                                {{-- RAW OT --}}
                                                <td>
                                                    {{ $dtr->raw_ot ?? '-' }}
                                                    <input type="hidden"
                                                        name="rows[{{ $dtr->id ?? 'new_'.$date }}][raw_ot]"
                                                        value="{{ $dtr->raw_ot ?? 0 }}">
                                                </td>

                                                {{-- OVERTIME --}}
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        name="rows[{{ $dtr->id ?? 'new_'.$date }}][overtime]"
                                                        class="border p-1 rounded w-full"
                                                        value="{{ $dtr->overtime ?? '' }}">
                                                </td>

                                                {{-- OT TYPE --}}
                                                <td class="w-48">
                                                    <select name="rows[{{ $dtr->id ?? 'new_'.$date }}][ot_type]"
                                                        class="border p-1 rounded w-full">

                                                        <option value="">Select</option>

                                                        <option value="REG OT" {{ ($dtr->ot_type ?? '') == 'REG OT' ? 'selected' : '' }}>Regular</option>
                                                        <option value="RD OT" {{ ($dtr->ot_type ?? '') == 'RD OT' ? 'selected' : '' }}>Restday</option>
                                                        <option value="SUN OT" {{ ($dtr->ot_type ?? '') == 'SUN OT' ? 'selected' : '' }}>Sun OT</option>
                                                        <option value="LEG HOL OT" {{ ($dtr->ot_type ?? '') == 'LEG HOL OT' ? 'selected' : '' }}>Legal Holiday</option>
                                                        <option value="SPL HOL OT" {{ ($dtr->ot_type ?? '') == 'SPL HOL OT' ? 'selected' : '' }}>Special Holiday</option>
                                                    </select>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">No cutoff selected</p>
                            @endif

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        @include('dtr.modals.uploadDtr')


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const overtimeInputs = document.querySelectorAll('input[name="overtime[]"]');

            overtimeInputs.forEach((input, index) => {

                // store last valid value
                let lastValid = input.value;

                input.addEventListener("input", function () {

                    const rawOtInputs = document.querySelectorAll('input[name="raw_ot[]"]');

                    let raw = parseFloat(rawOtInputs[index].value || 0);
                    let val = parseFloat(this.value || 0);

                    if (val > raw) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Overtime',
                            text: `Overtime cannot exceed Raw OT (${raw})`,
                            confirmButtonColor: '#3085d6',
                        });

                        // revert to last valid value (NOT raw_ot)
                        this.value = lastValid;

                        return;
                    }

                    // update last valid value
                    lastValid = this.value;
                });
            });
        });
        </script>
    </div>
</x-app-layout>