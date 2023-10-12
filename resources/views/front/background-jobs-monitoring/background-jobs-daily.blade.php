@extends('layouts.front')

@section('title')
    <title>Background Jobs - Daily Monitoring</title>
@endsection

@section('content')

<div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
    <div class="flex flex-col">
        <select id="navigationDropdown" class="w-56 text-white rounded-lg cursor-pointer focus:outline-none focus:border-blue-900 focus:shadow-outline-blue bg-darker-blue">
            <option value="{{ route('background-jobs-monitoring.daily') }}">Daily</option>
            <option value="{{ route('background-jobs-monitoring.data-amount') }}">Chart - Data Amount</option>
            <option value="{{ route('background-jobs-monitoring.duration') }}">Chart - Duration</option>
        </select>
        <div class="mt-6">
                <!-- Dropdown untuk Bulan -->
                <select id="month-selector" class="px-8 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ (int)date('m') === $month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <!-- Dropdown untuk Tahun -->
                <select id="year-selector" class="px-8 py-2 ml-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>

                <!-- Button untuk apply filter -->
                <button id="apply-filter-button" class="px-6 py-2 ml-2 text-white rounded-md bg-darker-blue focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">Filter</button>
        </div>
    </div>

                <div id="heatmap-container-type1"></div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #2FB489"></span> Normal Run
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #9BD95A"></span> Rerun Background Job
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #FFBB46"></span> Manual Run Background Job
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #FE504F"></span> Pending
                        </div>
                    </div>
                <div id="heatmap-container-type2"></div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #2FB489"></span> Normal Run
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #9BD95A"></span> Rerun Background Job
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #FFBB46"></span> Manual Run Background Job
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-5 h-5 mr-2" style="background-color: #FE504F"></span> Pending
                        </div>
                    </div>
</div>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        document.getElementById('navigationDropdown').addEventListener('change', function(){
            if (this.value){
                window.location.href = this.value;
            }
        })
        document.addEventListener('DOMContentLoaded', function () {

        applyFilter();

        document.getElementById('apply-filter-button').addEventListener('click', function () {
            applyFilter();
        });
    });
    function applyFilter() {
        const monthSelector = document.getElementById('month-selector');
        const yearSelector = document.getElementById('year-selector');

        const month = monthSelector.options[monthSelector.selectedIndex].value;
        const year = yearSelector.options[yearSelector.selectedIndex].value;

        const daysInMonth = new Date(year, month, 0).getDate(); // Dapatkan jumlah hari dalam bulan
        const dates = Array.from({ length: daysInMonth }, (_, i) => `${year}-${String(month).padStart(2, '0')}-${String(i + 1).padStart(2, '0')}`); // Buat array tanggal sesuai dengan jumlah hari dalam bulan

        // Fetch data berdasarkan bulan dan tahun yang dipilih
        fetchData(month, year, dates);
    }

    function fetchData(month, year, dates) {
        fetch(`/api/get-background-jobs-daily?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                initializeChart('heatmap-container-type1', data.type1.processes || {}, dates, 'Product');
                initializeChart('heatmap-container-type2', data.type2.processes || {}, dates, 'Non-Product');
            })
            .catch(error => console.error('Error fetching heatmap data:', error));
    }

    function initializeChart(containerId, data, dates, title) {
        let mappedData = Object.entries(data).flatMap(([date, processes]) =>
            Object.entries(processes).map(([process, status]) => ({
                date: date,
                process: process,
                status: status
            }))
        );

        // Mendefinisikan kategori berdasarkan semua proses yang ada
        let categories = mappedData.map(item => item.process).filter((value, index, self) => self.indexOf(value) === index);

        // Mendefinisikan peta status
        let statusMap = {
            'Normal Run': 0,
            'Rerun Background Job': 1,
            'Manual Run Background Job': 2,
            'Pending': 3
        };


        let seriesData = [];

        // Looping untuk setiap tanggal dan proses
        dates.forEach(date => {
            categories.forEach(process => {
                const foundData = mappedData.find(d => d.date === date && d.process === process); // Cari data yang sesuai dengan tanggal dan proses
                if (foundData) {
                    let y = categories.indexOf(process);
                    let x = dates.indexOf(date);
                    let value = statusMap.hasOwnProperty(foundData.status) ? statusMap[foundData.status] : null; // Jika status tidak ada di peta, maka value = null
                    seriesData.push({ x, y, value }); // Tambahkan data ke seriesData
                } else {
                    let y = categories.indexOf(process);
                    let x = dates.indexOf(date);
                    seriesData.push({ x, y, value: null });
                }
            });
        });

        Highcharts.chart(containerId, {
            chart: {
                type: 'heatmap',
                marginTop: 40,
                marginBottom: 80,
                plotBorderWidth: 1
            },
            title: {
                text: title
            },
            xAxis: {
                categories: dates.map(date => date.split('-')[2]), // Hanya tampilkan tanggal (DD)
                title: {
                    text: 'Tanggal'
                },
                labels: {
                    formatter: function () {
                        return this.value; // Tampilkan hanya tanggal, tanpa bulan/tahun
                    }
                }
            },
            yAxis: {
                categories: categories,
                title: null
            },
            colorAxis: {
                min: -1,
                max: Object.keys(statusMap).length - 1,
                stops: [
                    [-1, '#000000'], // black for unexpected/error value
                    [0, '#3060cf'], // blue for 'Normal Run'
                    [0.33, '#2ecf71'], // green for 'Rerun Background Job'
                    [0.66, '#ffe243'], // yellow for 'Manual Run Background Job'
                    [1, '#ff5050'] // red for 'Pending'
                ]
            },

            legend: {
                enabled : false
            },
            tooltip: {
                formatter: function() {
                    const statusMap = {
                        '-1': 'Unexpected/Error',
                        '0': 'Normal Run',
                        '1': 'Rerun Background Job',
                        '2': 'Manual Run Background Job',
                        '3': 'Pending',
                    };
                    const status = statusMap[String(this.point.value)];
                    return `<b>${this.series.xAxis.categories[this.point.x]} - ${this.series.yAxis.categories[this.point.y]}</b><br/>Status: ${status}`;
                },
            },
            series: [{
                name: 'Job Status',
                borderWidth: 1,
                data: seriesData,
                dataLabels: {
                    enabled: false,
                }
            }]
        });
    }

    </script>
@endsection

