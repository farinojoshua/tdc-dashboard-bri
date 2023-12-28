@extends('layouts.front')

@section('title')
    <title>SLA Monthly Report</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">User Management</h1>
        <div class="flex items-center justify-between gap-3 mt-10">
            <div class="w-1/3">
                <div class="flex flex-col p-2 text-white rounded-lg w-60 bg-dark-blue">
                    <span class="text-lg font-bold">Total Incident: <span id="totalIncident">Loading...</span></span>
                    <span class="text-lg">Done: <span id="doneIncidents">Loading...</span></span>
                    <span class="text-lg">Pending: <span id="pendingIncidents">Loading...</span></span>
                </div>
            </div>
            <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('user-management.sla-category') }}">SLA Monitoring</option>
                    <option value="{{ route('user-management.monthly-target') }}">Target Realization</option>
                    <option value="{{ route('user-management.request-by-type') }}">User Management Request</option>
                    <option value="{{ route('user-management.top-branch') }}">Top 5 Kanwil Request</option>
                </select>
            </div>
            <div class="w-1/3">
            <div class="flex items-end justify-end gap-4">
                <select id="month" class="form-control" style="display: inline-block; width: auto;" onchange="updateChartData()">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i, 1, date('Y'))) }}</option>
                    @endfor
                </select>
                <select id="year" class="form-control" style="display: inline-block; width: auto;" onchange="updateChartData()">
                    @for($i=date('Y'); $i>=2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            </div>
        </div>
        <canvas id="slaChart" class="mt-6"></canvas>
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

    let slaChart;

    function updateChartData() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        fetch(`/api/usman/get-sla-category-chart?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalIncident').textContent = data.totals.totalIncidents;
                document.getElementById('doneIncidents').textContent = data.totals.doneIncidents;
                document.getElementById('pendingIncidents').textContent = data.totals.pendingIncidents;

                const slaData = data.slaData;
                const labels = Object.keys(slaData);
                const meetSLAData = [];
                const overSLAData = [];

                labels.forEach(date => {
                    const day = new Date(date).getDate();
                    meetSLAData.push(slaData[date]['Meet SLA']);
                    overSLAData.push(slaData[date]['Over SLA']);
                });

                if (slaChart) {
                    slaChart.destroy();
                }

                const ctx = document.getElementById('slaChart').getContext('2d');
                slaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels.map(date => new Date(date).getDate()),
                        datasets: [{
                            label: 'Meet SLA',
                            data: meetSLAData,
                            borderColor: '#FFC107',
                            fill: false
                        }, {
                            label: 'Over SLA',
                            data: overSLAData,
                            borderColor: '#2ECC71',
                            fill: false
                        }]
                    },
                });
            });
    }

    updateChartData();
</script>
@endsection
