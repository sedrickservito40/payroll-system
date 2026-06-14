 <!-- CREATE MODAL -->
        <div x-show="open"
             x-cloak
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">

            <div class="bg-white w-full max-w-md p-6 rounded shadow">

                <h2 class="text-lg font-semibold mb-4">Add Employee</h2>

                <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-2 gap-3">

                        <input type="text" name="employee_number" placeholder="Employee Number"
                            class="border p-2 rounded col-span-2" required>

                        <input type="text" name="first_name" placeholder="First Name"
                            class="border p-2 rounded">

                        <input type="text" name="last_name" placeholder="Last Name"
                            class="border p-2 rounded">

                        <input type="text" name="middle_name" placeholder="Middle Name"
                            class="border p-2 rounded col-span-2">

                        <input type="text" name="department" placeholder="Department"
                            class="border p-2 rounded">

                        <input type="text" name="school" placeholder="School"
                            class="border p-2 rounded">

                        <input type="text" name="educational_attainment" placeholder="Educational Attainment"
                            class="border p-2 rounded">

                        <input type="text" name="degree" placeholder="Degree"
                            class="border p-2 rounded">

                        <input type="date" name="birthdate"
                            class="border p-2 rounded">

                        <input type="text" name="birthplace" placeholder="Birthplace"
                            class="border p-2 rounded">

                        <input type="time" name="shift_in_sched"
                            class="border p-2 rounded">

                        <input type="time" name="shift_out_sched"
                            class="border p-2 rounded">

                        <input type="text" name="sss_number" placeholder="SSS Number"
                            class="border p-2 rounded">

                        <input type="text" name="philhealth_number" placeholder="PhilHealth Number"
                            class="border p-2 rounded">

                        <input type="text" name="tin_number" placeholder="TIN Number"
                            class="border p-2 rounded">

                        <input type="text" name="pagibig_number" placeholder="Pag-IBIG Number"
                            class="border p-2 rounded">

                        <input type="file" name="emp_img"
                            class="border p-2 rounded col-span-2">

                        <input type="text" name="employee_level" placeholder="Employee Level"
                            class="border p-2 rounded">

                        <input type="number" step="0.01" name="employee_rate" placeholder="Employee Rate"
                            class="border p-2 rounded">

                        <input type="hidden" name="employee_status" value="PROBY"
                            class="border p-2 rounded">

                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button"
                                @click="open = false"
                                class="px-4 py-2 bg-gray-300 rounded">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
         <!-- CREATE MODAL -->