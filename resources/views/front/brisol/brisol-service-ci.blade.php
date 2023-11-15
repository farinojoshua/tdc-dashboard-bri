@extends('layouts.front')

@section('title')
    <title>Brisol Service CI</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Brisol Incident Management</h1>
    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3">
            <h2 id="totalRequests" class="text-2xl"></h2>
        </div>
        <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('brisol.service-ci') }}">Brisol Service CI</option>
                    <option value="{{ route('brisol.slm-status') }}">Brisol SLM Status</option>
                    <option value="{{ route('brisol.reported-source') }}">Brisol Reported Source</option>
                    <option value="{{ route('brisol.monthly-target') }}">Brisol Monthly Target</option>
                    <option value="{{ route('brisol.service-ci-top-issue') }}">Brisol Service CI Top Issue</option>
                </select>
        </div>
        <div class="w-1/3">
            <div class="flex flex-col items-end justify-end gap-4">
                <div class="flex overflow-hidden border rounded-lg">
                    <button type="button" onclick="updateMode('month', event)" id="monthButton" class="px-4 py-2 text-gray-600 {{ request('mode') == 'month' ? 'bg-darker-blue text-white' : '' }}">Per Month</button>
                    <button type="button" onclick="updateMode('date', event)" id="dateButton" class="px-4 py-2 text-gray-600 {{ request('mode') == 'date' ? 'bg-darker-blue text-white' : '' }}">Per Date</button>
                </div>
                <div class="flex">
                    <div id="monthDiv" style="{{ request('mode') == 'date' ? 'display:inline-block;' : 'display:none;' }}">
                        <select name="month" id="monthSelect" onchange="fetchData()">
                            @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="year" id="yearSelect" onchange="fetchData()">
                            @foreach(range(2020, date('Y')) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <canvas id="incidentChart" width="400" height="200" class="mt-6"></canvas>
</div>
@endsection

@section('script')
<script>
    document.getElementById("chartDropdownSelector").addEventListener("change", function() {
        const selectedURL = this.value;
        if (selectedURL) {
            window.location.href = selectedURL;
        }
    });

    let chart;
    let currentMode = '{{ request('mode', 'month') }}'; // Default mode to 'month'

    function updateMode(selectedMode, event) {
        event.preventDefault();
        currentMode = selectedMode;

        setButtonStyles();
        toggleMonthSelectVisibility();
        submitFormForModeChange();
    }

    function setButtonStyles() {
        document.getElementById('monthButton').classList.toggle('bg-darker-blue', currentMode === 'month');
        document.getElementById('monthButton').classList.toggle('text-white', currentMode === 'month');
        document.getElementById('dateButton').classList.toggle('bg-darker-blue', currentMode === 'date');
        document.getElementById('dateButton').classList.toggle('text-white', currentMode === 'date');
    }

    function toggleMonthSelectVisibility() {
        document.getElementById('monthDiv').style.display = currentMode === 'date' ? 'inline-block' : 'none';
    }

    function submitFormForModeChange() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = `{{ route('brisol.service-ci') }}?mode=${currentMode}`;

        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'mode';
        hiddenField.value = currentMode;

        form.appendChild(hiddenField);
        document.body.appendChild(form);
        form.submit();
    }


    function fetchData(){
        const year = document.getElementById('yearSelect').value;
        const month = document.getElementById('monthSelect').value;
        const mode = currentMode;

        fetch(`/api/brisol/get-service-ci-chart?year=${year}&month=${month}&mode=${mode}`)
            .then(response => response.json())
            .then(data => {
                const labels = mode === 'month' ? data.months : data.days;
                const ctx = document.getElementById('incidentChart').getContext('2d');

                document.getElementById('totalRequests').innerHTML = `Total Requests: ${data.totalRequests}`;

                const typeKeys = Object.keys(data.incidentCounts)
                    .filter(key => Object.keys(data.incidentCounts[key]).length > 0)
                    .map(key => Object.keys(data.incidentCounts[key]))
                    .flat()
                    .filter((value, index, self) => self.indexOf(value) === index);

                const datasets = [];

                function generateRandomColor() {
                    const r = Math.floor(Math.random() * 255);
                    const g = Math.floor(Math.random() * 255);
                    const b = Math.floor(Math.random() * 255);
                    return {
                        background: `rgba(${r}, ${g}, ${b}, 0.2)`,
                        border: `rgb(${r}, ${g}, ${b})`
                    };
                }

                typeKeys.forEach(type => {
                    const color = generateRandomColor();
                    const countsForType = labels.map(label => data.incidentCounts[label][type] || 0);

                    datasets.push({
                        label: `${type}`,
                        data: countsForType,
                        backgroundColor: color.background,
                        borderColor: color.border,
                        borderWidth: 1
                    });
                });

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                stacked: true
                            },
                            x: {
                                stacked: true
                            }
                        }
                    }
                });
            });
    }

    // Inisialisasi UI berdasarkan mode saat ini
    setButtonStyles();
    toggleMonthSelectVisibility();
    fetchData();
</script>
@endsection
