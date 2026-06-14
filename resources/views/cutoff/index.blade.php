<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">

        <h2 class="text-xl font-bold mb-4">Select Cutoff Start Date</h2>

        <!-- CURRENT CUT-OFF -->
        @if(session('cutoff_start'))
            <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">
                Currently Set Cutoff:
                <strong>{{ session('cutoff_start') }}</strong>
            </div>
        @else
            <div class="mb-4 p-3 bg-gray-100 text-gray-600 rounded">
                No cutoff selected yet.
            </div>
        @endif

        <form method="POST" action="{{ route('cutoff.set') }}">
            @csrf

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Choose Date (Only 10 or 25 allowed)
            </label>

            <input type="date"
                name="cutoff_start"
                id="cutoffDate"
                class="w-full border-gray-300 rounded mb-2"
                value="{{ session('cutoff_start') }}">

            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Set Cutoff
            </button>
        </form>

    </div>

    <script>
        const input = document.getElementById('cutoffDate');

        input.addEventListener('change', function () {
            const date = new Date(this.value);
            const day = date.getDate();

            if (day !== 10 && day !== 25) {
                this.value = "";

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Cutoff Date',
                    text: 'Only 10 or 25 of the month are allowed.',
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    </script>

</x-app-layout>