@extends('layouts.front')

@section('title')
    <title>Background Jobs - Duration</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <h1 class="mb-4 text-3xl font-semibold">Background Jobs - Duration</h1>

    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3"></div>

        <div class="w-1/2 mx-auto text-center">
            <select id="chartDropdown" class="w-full px-4 py-3 text-white border rounded bg-primary focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                @foreach($allChartData as $processName => $chartData)
                    <option value="{{ $processName }}">{{ $processName }}</option>
                @endforeach
            </select>
        </div>


        <div class="w-1/3">
            <form method="GET" action="{{ route('background-jobs-monitoring.duration') }}" class="flex items-center justify-end">
                <div class="mr-4">
                    <select name="mode" onchange="this.form.submit()">
                        <option value="month" {{ $mode == 'month' ? 'selected' : '' }}>Per Month</option>
                        <option value="date" {{ $mode == 'date' ? 'selected' : '' }}>Per Date</option>
                    </select>
                </div>

                @if($mode == 'date')
                <div class="mr-4">
                    <select name="month" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $chosenMonth == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if($mode == 'month')
                <div>
                    <select name="year" onchange="this.form.submit()">
                        @foreach(range(date('Y') - 5, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $chosenYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>
    </div>

    <h3 class="mt-4 text-xl font-semibold text-center" id="chartTitle">Loading...</h3>
    <canvas id="singleChart" class="mt-4"></canvas>
</div>
@endsection


@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartsData = @json($allChartData);
    const ctx = document.getElementById('singleChart').getContext('2d');
    let chart;

    function renderChart(processName) {
        const data = chartsData[processName];

        if (chart) {
            chart.destroy(); // Menghancurkan chart sebelumnya agar bisa membuat yang baru
        }

        document.getElementById('chartTitle').innerText = `Durasi Load Data ${processName}`;

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Duration to EIM (in seconds)',
                    data: data.durationsEIM,
                    borderColor: '#2B4CDE',
                    backgroundColor: '#2B4CDE',
                    fill: false
                }, {
                    label: 'Duration to S4GL (in seconds)',
                    data: data.durationsS4GL,
                    borderColor: '#FFC107',
                    backgroundColor: '#FFC107',
                    fill: false
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
    }

    document.getElementById('chartDropdown').addEventListener('change', function() {
        renderChart(this.value);
    });

    // Render the first chart on load
    renderChart(Object.keys(chartsData)[0]);
});
</script>
@endsection



