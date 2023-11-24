@extends('layouts.front')

@section('title')
    <title>Brisol SLM Status</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 bg-white rounded-lg shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold text-gray-800 sm:text-3xl">Brisol SLM Status</h1>
    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3">
        </div>
        <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('brisol.slm-status') }}">Brisol SLM Status</option>
                    <option value="{{ route('brisol.service-ci') }}">Brisol Service CI</option>
                    <option value="{{ route('brisol.reported-source') }}">Brisol Reported Source</option>
                    <option value="{{ route('brisol.monthly-target') }}">Brisol Monthly Target</option>
                    <option value="{{ route('brisol.service-ci-top-issue') }}">Brisol Service CI Top Issue</option>
                </select>
        </div>
        <div class="w-1/3">
            <div class="flex flex-col items-end justify-end gap-4">
                <div class="flex">
                    <div id="monthDiv" style="{{ request('mode') == 'date' ? 'display:inline-block;' : 'display:none;' }}">
                        <select name="month" id="monthSelect" onchange="fetchData()">
                            @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="yearFilter" class="mr-2 text-lg text-gray-700">Select Year:</label>
                        <select id="yearFilter" class="px-8 py-2 text-lg border rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-300">
                            @foreach(range(date('Y') - 3, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <canvas id="slmStatusChart" width="400" height="200" class="mt-6"></canvas>
</div>

@endsection

@section('script')
<script>
    document.getElementById("chartDropdownSelector").addEventListener("change", function() {
        window.location.href = this.value;
    });

    let currentChart;

    function loadChartData(year = document.getElementById('yearFilter').value) {
        fetch(`/api/brisol/get-slm-status-chart?year=${year}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('slmStatusChart').getContext('2d');
                if (currentChart) {
                    currentChart.destroy();
                }

                const uniqueStatuses = [...new Set(data.months.flatMap(month => Object.keys(data.data[month])))];

                const predefinedColors = ['#FFC107', '#2ECC71']; // Predefined colors
                let colorIndex = 0;

                const datasets = uniqueStatuses.map(status => {
                    let color;
                    if (colorIndex < predefinedColors.length) {
                        color = predefinedColors[colorIndex++];
                    } else {
                        color = randomColor(); // Generate random color
                    }

                    return {
                        label: status,
                        data: data.months.map(month => data.data[month][status] || 0),
                        borderColor: color,
                        tension: 0.1,
                        fill: false,
                    };
                });

                currentChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.months,
                        datasets: datasets,
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {}
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }

    function randomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadChartData();

        document.getElementById('yearFilter').addEventListener('change', function() {
            loadChartData(this.value);
        });
    });
</script>
@endsection

