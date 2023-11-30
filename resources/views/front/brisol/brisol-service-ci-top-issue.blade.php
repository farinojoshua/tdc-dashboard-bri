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
                <div class="flex gap-2">
                <div>
                    <select id="monthFilter" class="px-8 py-2 text-lg border rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 10)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                    <div>
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
    <!-- Adjusted container size and added shadow for the Overall Top Issues Chart -->
    <div class="w-full p-8 mx-auto mt-8 shadow-lg lg:w-2/3">
        <h2 class="mb-4 text-2xl font-semibold text-center text-gray-800 sm:text-3xl">Overall Top Issues</h2>
        <div class="rounded-lg chart-container" style="position: relative; height:50vh; width:90vw">
            <canvas id="overallTopIssuesChart"></canvas>
        </div>
    </div>
    <div id="pieChartsContainer" class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-2">
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById("chartDropdownSelector").addEventListener("change", function() {
        window.location.href = this.value;
    });

    let currentOverallChart;

    function loadOverallTopIssuesChart(year = document.getElementById('yearFilter').value, month = document.getElementById('monthFilter').value) {
        fetch(`/api/brisol/get-overall-top-issue?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('overallTopIssuesChart').getContext('2d');
                if (currentOverallChart) {
                    currentOverallChart.destroy();
                }

                const labels = data.map(issue => issue.issue);
                const counts = data.map(issue => issue.count);

                currentOverallChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Overall Top Issues',
                            data: counts,
                            backgroundColor: ['#FFC107', '#FB4141', '#2ECC71', '#FF8333', '#6C97DF', '#D3D3D3'],
                            borderColor: 'rgba(255, 255, 255, 0.6)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true, // Maintain aspect ratio
                        aspectRatio: 3, // Adjust this value to control the chart size
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 20,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const count = context.parsed;
                                        if (count !== null) {
                                            const total = context.chart._metasets[0].total;
                                            const percentage = (count / total * 100).toFixed(2) + '%';
                                            label += `${count} (${percentage})`;
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Overall Top Issues'
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading overall top issue chart data:', error);
            });
    }



    function loadPieCharts(year = document.getElementById('yearFilter').value, month = document.getElementById('monthFilter').value) {
        fetch(`/api/brisol/get-service-ci-top-issue?year=${year}&month=${month}`)
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
                                    '#FFC107',
                                    '#FB4141',
                                    '#2ECC71',
                                    '#FF8333',
                                    '#6C97DF',
                                    '#D3D3D3',
                                ],
                                borderColor: [
                                    '#FFC107',
                                    '#FB4141',
                                    '#2ECC71',
                                    '#FF8333',
                                    '#6C97DF',
                                    '#D3D3D3',
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
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            const count = context.parsed;
                                            if (count !== null) {
                                                const total = context.chart._metasets[0].total;
                                                const percentage = (count / total * 100).toFixed(2) + '%';
                                                label += `${count} (${percentage})`;
                                            }
                                            return label;
                                        }
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
        const year = document.getElementById('yearFilter').value;
        const month = document.getElementById('monthFilter').value;
        loadOverallTopIssuesChart(year, month);
        loadPieCharts(year, month);

        document.getElementById('yearFilter').addEventListener('change', function() {
            loadOverallTopIssuesChart(this.value, document.getElementById('monthFilter').value);
            loadPieCharts(this.value, document.getElementById('monthFilter').value);
        });

        document.getElementById('monthFilter').addEventListener('change', function() {
            loadOverallTopIssuesChart(document.getElementById('yearFilter').value, this.value);
            loadPieCharts(document.getElementById('yearFilter').value, this.value);
        });
    });
</script>
@endsection
