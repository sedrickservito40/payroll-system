<!-- MODAL -->
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Upload DTR File</h2>
                    <button @click="open = false" class="text-red-500 text-2xl">&times;</button>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="text-green-600 mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Upload Form -->
                <form action="{{ route('excel.upload') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input
                        type="file"
                        name="excel_file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="block w-full mb-4"
                    >

                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded"
                    >
                        Upload
                    </button>

                </form>

            </div>
        </div>