@extends('layouts.front')

@section('title')
    <title>Background Jobs - Line Chart</title>
@endsection


@section('content')
    <div class="mt-10"></div>
    <canvas id="myChart"></canvas>
@endsection

@section('script')
    <script>
        fetch('/api/background-jobs/chart-data')
        .then(response => response.json())
        .then(data => {
            var ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Data Amount to IEM',
                        data: data.dataIEM,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                    },
                    {
                        label: 'Data Amount to S4GL',
                        data: data.dataS4GL,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
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
        });
    </script>
@endsection
