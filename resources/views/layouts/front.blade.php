<!DOCTYPE html>
<html>
<head>
    <title>Deployment</title>

    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/calendar.js'])
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    {{-- create navbar --}}
    <div> <!-- Ini adalah kontainer untuk navbar dan list -->
    <nav class="flex justify-between p-6 bg-white font-poppins">
        <div>
            <a href="" class="text-3xl font-semibold">
                <span class="text-blue-600">TDC</span>Dashboard.
            </a>
        </div>
        <div class="flex gap-5">
            <a href="" class="text-xl font-bold text-dark-blue">Usman</a>
            <a href="" class="text-xl font-bold text-dark-blue">Brisol</a>
            <a href="" class="text-xl font-bold text-dark-blue">Deployment</a>
            <a href="" class="text-xl font-bold text-dark-blue">Background Jobs</a>
        </div>
    </nav>
    <div class="h-2 bg-gray-200"></div>
</div>

    <div class="container font-poppins">
         @yield('content')
    </div>

    {{-- add script yield --}}
    @yield('script')

</body>
</html>
