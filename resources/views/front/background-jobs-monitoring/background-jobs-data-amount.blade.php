@extends('layouts.front')

@section('title')
    <title>Background Jobs - Data Amount</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Background Jobs - Data Amount</h1>

    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3">
            <select id="navigationDropdown" class="w-56 text-white rounded-lg cursor-pointer focus:outline-none focus:border-blue-900 focus:shadow-outline-blue bg-darker-blue">
                <option value="{{ route('background-jobs-monitoring.data-amount') }}">Chart - Data Amount</option>
                <option value="{{ route('background-jobs-monitoring.duration') }}">Chart - Duration</option>
                <option value="{{ route('background-jobs-monitoring.daily') }}">Daily</option>
            </select>
        </div>

        <div class="w-1/2 mx-auto text-center">
            <select id="chartDropdown" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                @foreach($allChartData as $processName => $chartData)
                    <option value="{{ $processName }}">{{ $processName }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-1/3">
            <form method="GET" action="{{ route('background-jobs-monitoring.data-amount') }}" class="flex flex-col items-end justify-end gap-4">
                <div class="flex mr-4 overflow-hidden border rounded-lg">
                    <button type="button" onclick="setMode('month', event)" class="px-4 py-2 {{ $mode == 'month' ? 'bg-darker-blue text-white' : 'text-gray-600' }}">Month</button>
                    <button type="button" onclick="setMode('date', event)" class="px-4 py-2 {{ $mode == 'date' ? 'bg-darker-blue text-white' : 'text-gray-600' }}">Day</button>
                </div>

                @if($mode == 'date')
                <div class="mr-4">
                    <form action="{{ route('background-jobs-monitoring.data-amount') }}" method="GET">
                        <input type="hidden" name="mode" value="date">
                        <select name="month" onchange="this.form.submit()" class="form-select">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $chosenMonth == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @endif

                @if($mode == 'month')
                <div class="mr-4">
                    <select name="year" onchange="this.form.submit()" class="form-select">
                        @foreach(range(date('Y') - 5, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $chosenYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>
    </div>

    <h3 class="mt-4 text-xl font-semibold text-center sm:text-2xl" id="chartTitle">Loading...</h3>
    <canvas id="singleChart" class="mt-4"></canvas>
</div>

@endsection

@section('script')
<script>
document.getElementById('navigationDropdown').addEventListener('change', function() {
    if (this.value) {
            window.location.href = this.value;
    }
});

function setMode(selectedMode, event){
    event.preventDefault();

    const form = document.createElement('form');
    form.method = 'GET';
    form.action = "{{ route('background-jobs-monitoring.data-amount') }}";

    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'mode';
    hiddenField.value = selectedMode;

    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const chartsData = @json($allChartData);
    const ctx = document.getElementById('singleChart').getContext('2d');
    let chart;

    const chartDropdown = document.getElementById('chartDropdown');

    function renderChart(processName) {
        const data = chartsData[processName];

        if (chart) {
            chart.destroy();
        }

        document.getElementById('chartTitle').innerText = `Jumlah Data ${processName}`;

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Data to EIM',
                    data: data.eimAmounts,
                    borderColor: '#2B4CDE',
                    fill: false
                }, {
                    label: 'Data to S4GL',
                    data: data.s4glAmounts,
                    borderColor: '#FFC107',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
            }
        });
    }

    chartDropdown.addEventListener('change', function() {
        renderChart(this.value);
        localStorage.setItem('selectedChart', this.value); // Simpan pilihan chart
    });

    const savedChart = localStorage.getItem('selectedChart');
    if (savedChart) {
        chartDropdown.value = savedChart;
        renderChart(savedChart);
    } else {
        renderChart(Object.keys(chartsData)[0]);
    }
});

</script>
@endsection
