<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Styles / Scripts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body>
    @if ($errors)
    {{ $errors }}
    @endif
    <section class="p-6 bg-gray-100">

        <div class="max-w-sm mx-auto">
            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <label for="file1">Upload PC:</label><br>
                    <input type="file" name="pc" id="file1"><br><br>
                </div>

                <div>
                    <label for="file2">Upload CM1:</label><br>
                    <input type="file" name="cm1" id="file2"><br><br>
                </div>

                <div>
                    <label for="file3">Upload Cm2:</label><br>
                    <input type="file" name="cm2" id="file3"><br><br>
                </div>
                <div>
                    <label for="thickness">Thickness</label><br>
                    <input type="text" name="thickness" id="thickness"><br><br>
                </div>
                <button type="submit">Upload All</button>

        </div>

    </section>




</body>

</html>