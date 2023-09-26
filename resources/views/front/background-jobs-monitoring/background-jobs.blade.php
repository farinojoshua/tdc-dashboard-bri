@extends('layouts.front')

@section('content')
    <div id="heatmap-container-type1"></div>
    <div id="heatmap-container-type2"></div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/get-background-jobs')
        .then(response => response.json())
        .then(data => {
            initializeChart('heatmap-container-type1', data.type1 || {});
            initializeChart('heatmap-container-type2', data.type2 || {});
        })
        .catch(error => console.error('Error fetching heatmap data:', error));
});

function initializeChart(containerId, data) {
    let mappedData = Object.entries(data).flatMap(([date, processes]) =>
        Object.entries(processes).map(([process, statuses]) => ({
            date: date,
            process: process,
            status: statuses[0] // Atau logika lain berdasarkan struktur data Anda
        }))
    );

    let categories = [...new Set(mappedData.map(item => item.process))];
    let dates = [...new Set(mappedData.map(item => item.date))];

    // Mendefinisikan peta status ke nilai
    let statusMap = {
        'Normal Run': 0, // contoh: biru
        'Rerun Background Job': 1, // contoh: hijau
        'Manual Run Background Job': 2, // contoh: kuning
        'Pending': 3 // contoh: merah
    };

    let seriesData = mappedData.map(item => {
        let y = categories.indexOf(item.process);
        let x = dates.indexOf(item.date);
        let value = statusMap[item.status] || 0; // Gunakan statusMap untuk menetapkan nilai
        return { x, y, value };
    });

    Highcharts.chart(containerId, {
        chart: {
            type: 'heatmap',
            marginTop: 40,
            marginBottom: 80,
            plotBorderWidth: 1
        },
        title: {
            text: 'Heatmap Background Job'
        },
        xAxis: {
            categories: dates
        },
        yAxis: {
            categories: categories,
            title: null
        },
        colorAxis: {
            min: 0,
            max: Object.keys(statusMap).length - 1,
            stops: [
                [0, '#3060cf'], // biru untuk 'Normal Run'
                [0.33, '#2ecf71'], // hijau untuk 'Rerun Background Job'
                [0.66, '#ffeb3b'], // kuning untuk 'Manual Run Background Job'
                [1, '#ff5050'] // merah untuk 'Pending'
            ]
        },
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 320
        },
        series: [{
            name: 'Job Status',
            borderWidth: 1,
            data: seriesData,
            dataLabels: {
                enabled: true,
                color: '#000000'
            }
        }]
    });
}
</script>


@endsection

