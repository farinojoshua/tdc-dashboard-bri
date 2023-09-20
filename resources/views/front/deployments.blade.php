@extends('layouts.front')

@section('content')
    <div class="p-8 mx-auto max-w-7xl">

        <!-- Module Select -->


        <div class="flex justify-between p-6 mb-4 bg-gray-100 rounded shadow calendar-filter">
            <div>
                <!-- Left-side Filter Form -->
                 <select id="moduleSelect" class="p-2 mx-2 border rounded w-28">
            @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
            @endforeach
        </select>

        <!-- Year Select -->
        <select id="yearSelect" class="p-2 mx-2 border rounded w-28">
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
        </select>
            </div>

                <!-- Right-side Dropdown -->
               <div class="relative">
                    <button class="block px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200 dropdown-btn">
                        Chart
                    </button>
                    <div class="absolute right-0 z-10 hidden w-48 py-2 mt-2 bg-white border border-gray-300 rounded shadow dropdown-menu">
                        <a href="{{ route('deployments.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Chart</a>
                        <a href="{{ route('deployments.calendar') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Calendar</a>
                    </div>
                </div>
            </div>


        {{-- chart --}}



        <canvas id="myChart" width="400" height="200" class="mt-6"></canvas>
    </div>

@endsection

{{-- add script --}}
@section('script')

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownBtn = document.querySelector('.dropdown-btn');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        dropdownBtn.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });
    });
    </script>
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

    let max_value = 0;
    for (let serverType in datasets) {
        let serverTypeMax = Math.max(...datasets[serverType]);
        max_value = Math.max(max_value, serverTypeMax);
    }

    // Jika max_value kurang dari 10, tetapkan ke 10
    max_value = Math.max(max_value, 10);

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: chartDatasets
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: max_value,
                    ticks: {
                        stepSize: 1
                    }
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


