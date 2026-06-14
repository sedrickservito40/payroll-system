<x-app-layout>
    <div class="py-12" x-data="{ open: false, selected: null }" x-on:close-modal.window="open = false">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Upload Button -->
            <div class="mb-4">
                <button
                    type="button"
                    @click="open = true"
                    class="px-4 py-2 bg-green-600 text-white rounded"
                >
                    Upload DTR File
                </button>
            </div>

            <hr class="my-4">

            <!-- 2 COLUMN LAYOUT -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- LEFT: EMPLOYEES -->
                <div class="bg-white p-4 shadow rounded">
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

                <!-- RIGHT: DTR PER EMPLOYEE -->
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold mb-3">DTR Records</h2>

                    <template x-if="!selected">
                        <p class="text-gray-500">Select an employee to view DTR</p>
                    </template>

                    @foreach($employees as $employee)
                        <div x-show="selected === {{ $employee->id }}" x-cloak>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">

                                <div class="flex justify-between items-start">

                                    <!-- LEFT: ID + NAME -->
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            Employee No.
                                        </div>
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

                                    <!-- RIGHT: SHIFT INFO -->
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Shift Schedule</div>

                                        <div class="text-sm font-semibold text-gray-700">
                                            {{ $employee->shift_in_sched }} - {{ $employee->shift_out_sched }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                            @if($employee->dtrs->count())
                                <table class="w-full border text-sm">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th>Date</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Cutoff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->dtrs as $dtr)
                                            <tr>
                                                <td>{{ $dtr->date }}</td>
                                                <td>{{ $dtr->time_in }}</td>
                                                <td>{{ $dtr->time_out }}</td>
                                                <td>{{ $dtr->cutoff }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-sm text-gray-500">No DTR records</p>
                            @endif

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        @include('dtr.modals.uploadDtr')

    </div>
</x-app-layout>