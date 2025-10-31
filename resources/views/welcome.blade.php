<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>


<div class="flex flex-wrap -mx-2">
    <!-- Chart Column -->
    <div class="w-full md:w-10/12 px-2 mb-4 md:mb-0">
        <div class="w-full h-96 md:h-[500px]">
            <canvas id="myChart" width="1000" class="w-full h-full"></canvas>
        </div>
    </div>

    <!-- Metrics Column -->
    <!-- <div class="w-full md:w-2/12 px-2 flex flex-col justify-start space-y-2">
    <div>Min RWT (AVG-mm) - {{ number_format($data[0],2) }}</div>
        <div>Min RWT (AVG-%) - {{ number_format($data[1],2) }}</div>
        <div>Scan Axis (AVG-mm) - {{ $data[2] }}</div>
        <div>CM Std Dev (dB) - {{ number_format($data[3],2) }}</div>
    </div> -->
</div>

    <script>
        const orangeLine = @json($orangeLine).map(v => v * 0.455); // Array from controller
        const labels = @json($labels);
        //alert(labels);
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Labeling from 1 to 600
                datasets: [{
                        label: 'Series 1',
                        data: @json($blueLine).map(v => v * 100), // You can apply a scaling if necessary
                        borderColor: 'blue',
                        yAxisID: 'y',
                        pointRadius: 0,
                    },
                    {
                        label: 'CM_SUM',
                        data: orangeLine,
                        borderColor: 'orange',
                        yAxisID: 'y1',
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: false,
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
                            stepSize: 51
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
                            callback: function(value) {
                                return value;
                            } // optional, formats ticks
                        },
                        suggestedMin: -30, // negative side
                        suggestedMax: 30 // positive side
                    }
                }
            }
        });
    </script>
</body>

</html>