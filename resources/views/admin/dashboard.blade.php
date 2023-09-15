<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="p-4 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div id="calendar"></div>
            <div id="calendarLegend"></div>
        </div>
    </div>
    </div>

    <!-- Modal -->
    <div id="eventInfoModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="w-1/3 p-8 bg-white rounded-lg shadow-lg">
        <h5 class="mb-4 text-2xl" id="modalTitle">Event Info</h5>
        <div class="modal-body" id="modalBody">

        </div>
        <div class="mt-4 modal-footer">
        <button type="button" class="p-2 text-white bg-green-500 rounded" id="modalCloseButton">Close</button>
        </div>
    </div>
    </div>

    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Looping untuk setiap modul dan membuat canvas elemen untuk chart -->
        @foreach ($chartData as $moduleName => $data)
            <div class="p-4 bg-white rounded shadow">
                <h3 class="mb-4 text-xl">{{ $moduleName }}</h3>
                <canvas id="chart-{{ $moduleName }}" class="w-full" height="200"></canvas>
                <p id="total-{{ $moduleName }}">Total: </p>
            </div>
        @endforeach
    </div>
    </div>




    <x-slot name="script">
        <script src="{{ mix('js/app.js') }}"></script>
        <script>
        // Kode JavaScript untuk inisialisasi Chart.js
        @foreach ($chartData as $moduleName => $data)
            const ctx{{ $loop->index }} = document.getElementById('chart-{{ $moduleName }}').getContext('2d');
            const labels{{ $loop->index }} = Object.keys({!! json_encode($data) !!});
            const data{{ $loop->index }} = Object.values({!! json_encode($data) !!});

            // Menghitung total
            const total{{ $loop->index }} = data{{ $loop->index }}.reduce((acc, val) => acc + val, 0);

            // Menampilkan total
            document.getElementById('total-{{ $moduleName }}').innerText += total{{ $loop->index }};

            const myBarChart{{ $loop->index }} = new Chart(ctx{{ $loop->index }}, {
                type: 'bar',
                data: {
                    labels: labels{{ $loop->index }},
                    datasets: [{
                        label: '# of Deployments',
                        data: data{{ $loop->index }},
                        backgroundColor: 'rgba(75, 192, 102, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @endforeach
        </script>

    </x-slot>
</x-app-layout>
