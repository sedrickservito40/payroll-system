<x-app-layout>
    <div class="py-12" x-data="{ open: false }" x-on:close-modal.window="open = false">

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

            <!-- DTR Table -->
            <h2 class="text-lg font-semibold mb-2">Uploaded DTR Records</h2>

            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Employee #</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Cutoff</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dtrs as $dtr)
                        <tr>
                            <td>{{ $dtr->employee_number }}</td>
                            <td>{{ $dtr->date }}</td>
                            <td>{{ $dtr->time_in }}</td>
                            <td>{{ $dtr->time_out }}</td>
                            <td>{{ $dtr->cutoff }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        @include('dtr.modals.uploadDtr')

    </div>
</x-app-layout>