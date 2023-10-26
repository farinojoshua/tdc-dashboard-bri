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
                    <option value="{{ route('user-management.top-branch') }}">Top 5 Ukker Request</option>
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

            let url = `/api/usman/get-top-branch-request-chart?month=${month}&year=${year}`;

            try {
                const response = await fetch(url);
                const branches = await response.json();

                const branchNames = branches.map(branch => branch.name);
                const requestCounts = branches.map(branch => branch.total_requests);

                // Update Pie Chart
                const ctx = document.getElementById('branchPieChart').getContext('2d');
                if (branchPieChart) {
                    branchPieChart.destroy(); // Destroy existing chart before updating
                }
                branchPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: branchNames,
                        datasets: [{
                            label: 'Total Requests',
                            data: requestCounts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
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

