<x-app-layout>
    <div class="py-12" x-data="{ open: false, selected: null }" x-on:close-modal.window="open = false">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Upload Button --}}
            <div class="mb-2">
                <button
                    type="button"
                    @click="open = true"
                    class="px-4 py-2 bg-green-600 text-white rounded"
                >
                    Upload DTR File
                </button>
            </div>

            <hr class="my-4">

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                {{-- LEFT: EMPLOYEES --}}
                <div class="md:col-span-3 bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold mb-3">Employees</h2>

                    <ul class="space-y-2">
                        @foreach($employees as $employee)
                            <li
                                class="border p-2 rounded cursor-pointer hover:bg-gray-100"
                                @click="selected = {{ $employee->id }}"
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
                        <div x-show="selected === {{ $employee->id }}" x-cloak>

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
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
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

                <option value="REG" {{ ($dtr->ot_type ?? '') == 'REG' ? 'selected' : '' }}>Regular</option>
                <option value="RESTDAY" {{ ($dtr->ot_type ?? '') == 'RESTDAY' ? 'selected' : '' }}>Restday</option>
                <option value="HOLIDAY" {{ ($dtr->ot_type ?? '') == 'HOLIDAY' ? 'selected' : '' }}>Holiday</option>
                <option value="SPECIAL" {{ ($dtr->ot_type ?? '') == 'SPECIAL' ? 'selected' : '' }}>Special</option>
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