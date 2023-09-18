@extends('layouts.front')

@section('content')
    <div class="p-8 mx-auto max-w-7xl">

        <select id="moduleSelect">
        @foreach($modules as $module)
            <option value="{{ $module->id }}">{{ $module->name }}</option>
        @endforeach
        </select>

        {{-- year select --}}
        <select id="yearSelect">
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
        </select>

        {{-- chart --}}



        <canvas id="myChart" width="400" height="200"></canvas>
    </div>

@endsection

{{-- add script --}}
@section('script')
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
    var myChart;

    function fetchData() {
        var selectedModule = document.getElementById('moduleSelect').value;
        var selectedYear = document.getElementById('yearSelect').value;
        fetch(`api/deployments/chart-data?module_id=${selectedModule}&year=${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                if (myChart) {
                    myChart.destroy();
                }
                var ctx = document.getElementById('myChart').getContext('2d');
                renderChart(ctx, data);
            });
    }


    function renderChart(ctx, data) {
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var datasets = {};
    var dataPoints = Array(12).fill(0);

    data.forEach(function(record) {
        if (!datasets[record.server_type]) {
            datasets[record.server_type] = Array(12).fill(0);
        }
        datasets[record.server_type][record.month - 1] = record.count;
    });

    var chartDatasets = [];
    var stackCounter = 0;

    for (var serverType in datasets) {
        var color = randomColor();
        chartDatasets.push({
            label: serverType,
            data: datasets[serverType],
            borderColor: color,
            borderWidth: 1,
            fill: true,  // change this to true
            backgroundColor: color,  // add this line
            stack: 'Stack ' + stackCounter
        });
        stackCounter++;
    }

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: chartDatasets
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


    function randomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    document.addEventListener("DOMContentLoaded", function() {
        fetchData();  // fetch data when the page loads
        var moduleSelect = document.getElementById('moduleSelect');
        var yearSelect = document.getElementById('yearSelect');
        moduleSelect.addEventListener('change', fetchData);  // fetch data again when the selected module changes
        yearSelect.addEventListener('change', fetchData);  // fetch data again when the selected year changes
    });

    </script>
@endsection


