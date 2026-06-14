<x-app-layout>
    <div x-data="{ open: false, viewEmp: null, editEmp: null }">

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-semibold">Employees</h2>

                <button type="button"
                     @click="open = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    + Add Employee
                </button>
            </div>

            <!-- TABLE -->
            <div class="bg-white shadow p-4 rounded">

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">Employee #</th>
                            <th class="p-2 border">First Name</th>
                            <th class="p-2 border">Last Name</th>
                            <th class="p-2 border">Department</th>
                            <th colspan="2" class="p-2 border">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($employees as $emp)
                            <tr>
                                <td class="p-2 border">{{ $emp->employee_number }}</td>
                                <td class="p-2 border">{{ $emp->first_name }}</td>
                                <td class="p-2 border">{{ $emp->last_name }}</td>
                                <td class="p-2 border">{{ $emp->department }}</td>
                                <td class="p-2 border text-center space-x-2">
                                    <button
                                        type="button"
                                        @click.stop="viewEmp = @js([
                                            'id' => $emp->id,
                                            'employee_number' => $emp->employee_number,
                                            'first_name' => $emp->first_name,
                                            'last_name' => $emp->last_name,
                                            'middle_name' => $emp->middle_name,
                                            'department' => $emp->department,
                                            'school' => $emp->school,
                                            'educational_attainment' => $emp->educational_attainment,
                                            'degree' => $emp->degree,
                                            'birthdate' => $emp->birthdate,
                                            'birthplace' => $emp->birthplace,
                                            'shift_in_sched' => $emp->shift_in_sched,
                                            'shift_out_sched' => $emp->shift_out_sched,
                                            'sss_number' => $emp->sss_number,
                                            'philhealth_number' => $emp->philhealth_number,
                                            'tin_number' => $emp->tin_number,
                                            'pagibig_number' => $emp->pagibig_number,
                                            'employee_level' => $emp->employee_level,
                                            'employee_rate' => $emp->employee_rate,
                                            'emp_img' => $emp->emp_img ? base64_encode($emp->emp_img) : null,
                                        ])"
                                        class="text-blue-600 hover:text-blue-800"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 
                                                0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 
                                                7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="p-2 border text-center space-x-2">
                                    <button
                                            type="button"
                                            @click.stop="editEmp = @js([
                                                'id' => $emp->id,
                                                'employee_number' => $emp->employee_number,
                                                'first_name' => $emp->first_name,
                                                'last_name' => $emp->last_name,
                                                'middle_name' => $emp->middle_name,
                                                'department' => $emp->department,
                                                'school' => $emp->school,
                                                'educational_attainment' => $emp->educational_attainment,
                                                'degree' => $emp->degree,
                                                'birthdate' => $emp->birthdate,
                                                'birthplace' => $emp->birthplace,
                                                'sss_number' => $emp->sss_number,
                                                'philhealth_number' => $emp->philhealth_number,
                                                'tin_number' => $emp->tin_number,
                                                'pagibig_number' => $emp->pagibig_number,
                                                'employee_level' => $emp->employee_level,
                                                'employee_rate' => $emp->employee_rate,
                                                'employee_status' => $emp->employee_status,
                                            ])"
                                            class="text-green-600"
                                        >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                                                2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    No Records Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $employees->links() }}
                </div>

            </div>

        </div>
            @include('employees.modals.create')
            @include('employees.modals.view')
            @include('employees.modals.edit')
            
    </div>
</x-app-layout>