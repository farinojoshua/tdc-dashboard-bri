@extends('layouts.front')

@section('title')
    <title>Brisol Reported Source</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Brisol Reported Source</h1>
    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3">
        </div>
        <div class="w-1/2 mx-auto text-center">
            <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                <option value="{{ route('brisol.reported-source') }}">Brisol Reported Source</option>
                <option value="{{ route('brisol.slm-status') }}">Brisol SLM Status</option>
                <option value="{{ route('brisol.service-ci-top-issue') }}">Brisol Service CI Top Issue</option>
                <option value="{{ route('brisol.service-ci') }}">Brisol Service CI</option>
                <option value="{{ route('brisol.monthly-target') }}">Brisol Monthly Target</option>
            </select>
        </div>
        <div class="w-1/3">
            <div class="flex flex-col items-end justify-end gap-4">
                <div class="mb-5">
                    <label for="yearFilter" class="mr-2">Select Year:</label>
                    <select id="yearFilter" onchange="loadChartData()">
                        @foreach(range(2020, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <canvas id="reportedSourceChart" width="400" height="200" class="mt-6"></canvas>
</div>

@endsection


@section('script')
<script>
    document.getElementById("chartDropdownSelector").addEventListener("change", function() {
        window.location.href = this.value;
    });

    let currentChart;

    // Predefined colors array
    const predefinedColors = [
        '#FFC107', '#2ECC71', '#152C5B', '#FF8333', '#2B4CDE',
        '#EE1515', '#BFBFBF', '#17A2B8', '#6C97DF', '#262628',
        '#CCDAFCCC', '#FF6A88CC'
    ];

    function getColor(index) {
        if (index < predefinedColors.length) {
            return predefinedColors[index];
        } else {
            // Generate random color with transparency
            return '#' + Math.floor(Math.random() * 16777215).toString(16);
        }
    }

    function loadChartData(year = document.getElementById('yearFilter').value) {
        fetch(`/api/brisol/get-reported-source-chart?year=${year}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('reportedSourceChart').getContext('2d');
                if (currentChart) {
                    currentChart.destroy();
                }
                const uniqueSources = [...new Set(data.months.flatMap(month => Object.keys(data.data[month])))];

                const datasets = uniqueSources.map((source, index) => ({
                    label: source,
                    data: data.months.map(month => data.data[month][source] || 0),
                    backgroundColor: getColor(index),
                }));

                currentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.months,
                        datasets: datasets,
                    },
                    options: {
                        scales: {
                            y: {
                                stacked: true,
                                beginAtZero: true
                            },
                            x: {
                                stacked: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadChartData();

        document.getElementById('yearFilter').addEventListener('change', function() {
            loadChartData(this.value);
        });
    });
</script>
@endsection

