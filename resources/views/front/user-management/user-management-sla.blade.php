@extends('layouts.front')

@section('title')
    <title>SLA Performance Dashboard</title>
@endsection

@section('content')

<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <!-- Dropdowns untuk filter bulan dan tahun -->
    <div class="filter-section">
        <label for="monthFilter">Bulan:</label>
        <select id="monthFilter">
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
            @endforeach
        </select>

        <label for="yearFilter">Tahun:</label>
        <select id="yearFilter">
            @foreach(range(2021, date('Y')) as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>

        <button id="filterButton">Filter</button>
    </div>

    <!-- Canvas untuk chart -->
    <div class="chart-section">
        <canvas id="slaChart"></canvas>
    </div>
</div>


@endsection

@section('script')
<script>
    var slaChart;

    window.onload = function() {
        var ctx = document.getElementById('slaChart').getContext('2d');
        slaChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Meet SLA', 'Over SLA'],
                datasets: [{
                    data: [],  // Initial data is empty
                    backgroundColor: ['green', 'red']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Kinerja SLA'
                }
            }
        });

        // Set default month and year to current month and year
        var currentDate = new Date();
        var currentMonth = currentDate.getMonth() + 1;
        var currentYear = currentDate.getFullYear();

        document.getElementById('monthFilter').value = currentMonth;
        document.getElementById('yearFilter').value = currentYear;

        updateChart(currentMonth, currentYear);
    }

    document.getElementById('filterButton').addEventListener('click', function() {
        var selectedMonth = document.getElementById('monthFilter').value;
        var selectedYear = document.getElementById('yearFilter').value;
        updateChart(selectedMonth, selectedYear);
    });

    function updateChart(month, year) {
        fetch(`/api/usman/get-sla-category-chart?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                slaChart.data.datasets[0].data = [data.meetSLA, data.overSLA];
                slaChart.update();
            });
    }
</script>
@endsection
