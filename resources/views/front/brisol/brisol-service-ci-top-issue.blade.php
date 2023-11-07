@extends('layouts.front')

@section('title')
    <title>Brisol Top Issue</title>
@endsection

@section('content')
<div class="p-10 mx-auto my-10 bg-white rounded-lg shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold text-gray-800 sm:text-3xl">Brisol Top Issue</h1>
    <div class="flex items-center justify-between mt-12">
        <div class="w-1/3">
        </div>
        <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector" class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('brisol.service-ci-top-issue') }}">Brisol Service CI Top Issue</option>
                    <option value="{{ route('brisol.slm-status') }}">Brisol SLM Status</option>
                    <option value="{{ route('brisol.service-ci') }}">Brisol Service CI</option>
                    <option value="{{ route('brisol.reported-source') }}">Brisol Reported Source</option>
                    <option value="{{ route('brisol.monthly-target') }}">Brisol Monthly Target</option>
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
    <div id="pieChartsContainer" class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-2 lg:grid-cols-3">
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById("chartDropdownSelector").addEventListener("change", function() {
        window.location.href = this.value;
    });

    function loadPieCharts(year = document.getElementById('yearFilter').value) {
        fetch(`/api/brisol/get-service-ci-top-issue?year=${year}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('pieChartsContainer');
                container.innerHTML = '';

                data.forEach(serviceCiData => {
                    const chartDiv = document.createElement('div');
                    chartDiv.className = 'p-4 border rounded-lg shadow-lg flex flex-col items-center';

                    const title = document.createElement('h3');
                    title.className = 'mb-3 text-lg font-semibold';
                    title.textContent = `Top Issues for ${serviceCiData.service_ci}`;
                    chartDiv.appendChild(title);

                    const chartContainer = document.createElement('div');
                    chartContainer.className = 'chart-container w-full';
                    chartContainer.style.position = 'relative';
                    chartContainer.style.display = 'flex';
                    chartContainer.style.justifyContent = 'center';

                    const canvas = document.createElement('canvas');
                    chartContainer.appendChild(canvas);
                    chartDiv.appendChild(chartContainer);
                    container.appendChild(chartDiv);

                    const ctx = canvas.getContext('2d');
                    const labels = serviceCiData.issues.map(issue => issue.issue);
                    const counts = serviceCiData.issues.map(issue => issue.count);

                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `Top Issues for ${serviceCiData.service_ci}`,
                                data: counts,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(199, 199, 199, 0.2)',
                                    'rgba(83, 102, 255, 0.2)',
                                    'rgba(40, 167, 69, 0.2)',
                                    'rgba(255, 99, 71, 0.2)',

                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(199, 199, 199, 0.2)',
                                    'rgba(83, 102, 255, 0.2)',
                                    'rgba(40, 167, 69, 0.2)',
                                    'rgba(255, 99, 71, 0.2)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
                                    labels: {
                                        boxWidth: 20,
                                        padding: 20,
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: `Top Issues for ${serviceCiData.service_ci}`
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            }
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error loading pie chart data:', error);
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadPieCharts();

        document.getElementById('yearFilter').addEventListener('change', function() {
            loadPieCharts(this.value);
        });
    });
</script>
@endsection
