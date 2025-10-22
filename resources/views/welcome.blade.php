<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    Min RWT (AVG-mm) - {{ number_format($data[0],2) }}

    Min RWT (AVG-%) - {{ number_format($data[1],2) }}
    Scan Axis (AVG-mm) - {{ $data[2] }}
    CM Std Dev (dB) - {{ number_format($data[3],2) }}




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="w-100" style="width: 100%; height: 600px;">
        <canvas id="myChart"></canvas>
    </div>
    <script>
        const orangeLine = @json($orangeLine); // Array from controller
        const labels = @json($labels);

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Labeling from 1 to 600
                datasets: [{
                        label: 'Blue Line',
                        data: @json($blueLine).map(v => v * 100), // You can apply a scaling if necessary
                        borderColor: 'blue',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Orange Line',
                        data: orangeLine,
                        borderColor: 'orange',
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        min: 0,
                        max: labels.length,
                        ticks: {
                            stepSize: 50
                        }
                    },
                    y: {
                        type: 'linear',
                        position: 'left',
                        min: 0, // Start from 0%
                        max: 100,
                        ticks: {
                            callback: value => value + '%',
                            stepSize: 10
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            stepSize: 5,
                            max: 20
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>