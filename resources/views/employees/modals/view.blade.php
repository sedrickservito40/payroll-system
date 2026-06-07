<!-- View modal -->
<div x-cloak
     x-show="viewEmp !== null"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white flex items-center gap-4">

            <!-- Employee Image -->
            <template x-if="viewEmp?.emp_img">
                <img
                    :src="'data:image/jpeg;base64,' + viewEmp.emp_img"
                    class="w-16 h-16 rounded-full object-cover border-2 border-white shadow"
                >
            </template>

            <!-- Fallback Initials -->
            <template x-if="!viewEmp?.emp_img">
                <div
                    class="w-16 h-16 rounded-full flex items-center justify-center bg-gray-300 text-gray-700 font-bold border-2 border-white shadow"
                    x-text="getInitials(viewEmp?.first_name, viewEmp?.last_name)"
                ></div>
            </template>

            <div>
                <h2 class="text-xl font-semibold">Employee Details</h2>
                <p class="text-sm opacity-90">View full profile information</p>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div class="space-y-2">
                <p>
                    <span class="font-semibold">Employee #:</span>
                    <span x-text="viewEmp?.employee_number"></span>
                </p>

                <p>
                    <span class="font-semibold">Name:</span>
                    <span x-text="(viewEmp?.first_name ?? '') + ' ' + (viewEmp?.last_name ?? '')"></span>
                </p>

                <p>
                    <span class="font-semibold">Middle Name:</span>
                    <span x-text="viewEmp?.middle_name"></span>
                </p>

                <p>
                    <span class="font-semibold">Birthdate:</span>
                    <span x-text="viewEmp?.birthdate"></span>
                </p>

                <p>
                    <span class="font-semibold">Birthplace:</span>
                    <span x-text="viewEmp?.birthplace"></span>
                </p>
            </div>

            <div class="space-y-2">
                <p>
                    <span class="font-semibold">Department:</span>
                    <span x-text="viewEmp?.department"></span>
                </p>

                <p>
                    <span class="font-semibold">School:</span>
                    <span x-text="viewEmp?.school"></span>
                </p>

                <p>
                    <span class="font-semibold">Degree:</span>
                    <span x-text="viewEmp?.degree"></span>
                </p>

                <p>
                    <span class="font-semibold">Level:</span>
                    <span x-text="viewEmp?.employee_level"></span>
                </p>

                <p>
                    <span class="font-semibold">Rate:</span>
                    <span x-text="viewEmp?.employee_rate
                        ? new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(viewEmp.employee_rate)
                        : ''">
                    </span>
                </p>
            </div>

            <div class="md:col-span-2 border-t pt-4 grid grid-cols-1 md:grid-cols-4 gap-2 text-xs">

                <p>
                    <span class="font-semibold">SSS:</span>
                    <span x-text="viewEmp?.sss_number"></span>
                </p>

                <p>
                    <span class="font-semibold">PhilHealth:</span>
                    <span x-text="viewEmp?.philhealth_number"></span>
                </p>

                <p>
                    <span class="font-semibold">TIN:</span>
                    <span x-text="viewEmp?.tin_number"></span>
                </p>

                <p>
                    <span class="font-semibold">Pag-IBIG:</span>
                    <span x-text="viewEmp?.pagibig_number"></span>
                </p>

            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button @click="viewEmp = null"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">
                Close
            </button>
        </div>

    </div>
</div>
<!-- View modal -->