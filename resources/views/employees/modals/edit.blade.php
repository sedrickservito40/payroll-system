<!-- Edit modal -->
<template x-if="editEmp">
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white w-full max-w-lg p-6 rounded shadow">

            <h2 class="text-lg font-semibold mb-4">Edit Employee</h2>

            <form method="POST" :action="'/employees/' + editEmp.id">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">

                    <input type="text" name="employee_number"
                        x-model="editEmp.employee_number"
                        class="border p-2 rounded col-span-2">

                    <input type="text" name="first_name"
                        x-model="editEmp.first_name"
                        class="border p-2 rounded">

                    <input type="text" name="last_name"
                        x-model="editEmp.last_name"
                        class="border p-2 rounded">

                    <input type="text" name="middle_name"
                        x-model="editEmp.middle_name"
                        class="border p-2 rounded col-span-2">

                    <input type="text" name="department"
                        x-model="editEmp.department"
                        class="border p-2 rounded">

                    <input type="text" name="school"
                        x-model="editEmp.school"
                        class="border p-2 rounded">

                    <input type="text" name="degree"
                        x-model="editEmp.degree"
                        class="border p-2 rounded">

                    <input type="date" name="birthdate"
                        x-model="editEmp.birthdate"
                        class="border p-2 rounded">

                    <input type="text" name="birthplace"
                        x-model="editEmp.birthplace"
                        class="border p-2 rounded">

                    <input type="text" name="sss_number"
                        x-model="editEmp.sss_number"
                        class="border p-2 rounded">

                    <input type="text" name="philhealth_number"
                        x-model="editEmp.philhealth_number"
                        class="border p-2 rounded">

                    <input type="text" name="tin_number"
                        x-model="editEmp.tin_number"
                        class="border p-2 rounded">

                    <input type="text" name="pagibig_number"
                        x-model="editEmp.pagibig_number"
                        class="border p-2 rounded">

                    <input type="text" name="employee_level"
                        x-model="editEmp.employee_level"
                        class="border p-2 rounded">

                    <input type="number" step="0.01" name="employee_rate"
                        x-model="editEmp.employee_rate"
                        class="border p-2 rounded">

                    <select
                        name="employee_status"
                        x-model="editEmp.employee_status"
                        class="border p-2 rounded"
                    >
                        <option value="">Select Status</option>
                        <option value="REG">Regular</option>
                        <option value="PROBY">Probationary</option>
                        <option value="CON">Contractual</option>
                        <option value="RES">Resigned</option>
                        <option value="TER">Terminated</option>
                    </select>

                </div>

                <div class="flex justify-end gap-2 mt-4">

                    <button type="button"
                        @click="editEmp = null"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</template>
<!-- Edit modal -->