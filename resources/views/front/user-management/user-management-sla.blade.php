@extends('layouts.front')

@section('title')
    <title>SLA Performance Dashboard</title>
@endsection

@section('content')

<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
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
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Meet SLA',
                        data: [],
                        borderColor: 'green',
                        fill: false
                    },
                    {
                        label: 'Over SLA',
                        data: [],
                        borderColor: 'red',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Kinerja SLA'
                }
            }
        });

        var currentYear = (new Date()).getFullYear();
        document.getElementById('yearFilter').value = currentYear;
        updateChart(currentYear);
    }

    document.getElementById('filterButton').addEventListener('click', function() {
        var selectedYear = document.getElementById('yearFilter').value;
        updateChart(selectedYear);
    });

    function updateChart(year) {
        fetch(`/api/usman/get-sla-category-chart?year=${year}`)
            .then(response => response.json())
            .then(data => {
                slaChart.data.datasets[0].data = data.map(item => item.meetSLA);
                slaChart.data.datasets[1].data = data.map(item => item.overSLA);
                slaChart.update();
            });
    }
</script>


@endsection
