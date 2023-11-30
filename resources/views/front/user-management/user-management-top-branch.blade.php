    @extends('layouts.front')

    @section('title')
        <title>User Management Request</title>
    @endsection

    @section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">User Management</h1>
        <div class="flex items-center justify-between gap-3">
            <div class="w-1/3">
            </div>
            <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('user-management.top-branch') }}">Top 5 Kanwil Request</option>
                    <option value="{{ route('user-management.request-by-type') }}">User Management Request</option>
                    <option value="{{ route('user-management.monthly-target') }}">Target Realization</option>
                    <option value="{{ route('user-management.sla-category') }}">SLA Monitoring</option>
                </select>
            </div>
            <div class="w-1/3">
                <div class="flex items-end justify-end gap-4">
                    <div id="monthFilter" onchange="fecthData()">
                        <select name="month" id="monthSelect">
                            @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="yearFilter" onchange="fecthData()">
                        <select name="year" id="yearSelect">
                            @foreach(range(2020, date('Y')) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <canvas id="branchPieChart" class="mt-6 w-full max-w-lg h-[300px] mx-auto"></canvas>
    </div>
    @endsection

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.getElementById("chartDropdownSelector").addEventListener("change", function() {
            const selectedURL = this.value;
            if (selectedURL) {
                window.location.href = selectedURL;
            }
        });
        let branchPieChart;

    async function fecthData() {
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;

        // Ubah URL untuk mengambil data kanwil
        let url = `/api/usman/get-top-kanwil-request-chart?month=${month}&year=${year}`;

        try {
            const response = await fetch(url);
            const kanwils = await response.json();

            const kanwilNames = kanwils.map(kanwil => kanwil.kanwil_name);
            const requestCounts = kanwils.map(kanwil => kanwil.total_requests);

            // Perbarui Pie Chart
            const ctx = document.getElementById('branchPieChart').getContext('2d');
            if (branchPieChart) {
                branchPieChart.destroy(); // Hancurkan grafik yang ada sebelum memperbarui
            }
            branchPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: kanwilNames,
                    datasets: [{
                        label: 'Total Requests',
                        data: requestCounts,
                        backgroundColor: [
                            '#FFC107',
                            '#2ECC71',
                            '#6C97DF',
                            '#EE1515',
                            '#FF8333'
                        ],
                        borderWidth: 1
                    }]
                }
            });

        } catch (error) {
            console.error("There was an error fetching the data", error);
        }
    }

        // Call this function when the page loads
        window.onload = fecthData;
    </script>
    @endsection

