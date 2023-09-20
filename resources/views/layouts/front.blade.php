<!DOCTYPE html>
<html>
<head>
    <title>Deployment</title>

    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/calendar.js'])
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
        <div class="relative flex gap-5">
            <a href="" class="text-xl font-bold text-dark-blue">Usman</a>
            <a href="" class="text-xl font-bold text-dark-blue">Brisol</a>
            <div class="relative inline-block text-left">
                <a href="#" class="inline-flex justify-center w-full text-xl font-bold text-dark-blue">
                    Deployment
                </a>
                <div class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <a href="{{ route('deployments.calendar') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Calendar</a>
                        <a href="{{ route('deployments.index') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Chart</a>
                    </div>
                </div>
            </div>
            <a href="" class="text-xl font-bold text-dark-blue">Background Jobs</a>
        </div>
    </nav>
    <div class="h-2 bg-gray-200"></div>
</div>


    <div class="container mx-auto font-poppins">
         @yield('content')
    </div>

    {{-- add script yield --}}
    @yield('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.querySelector('.relative.inline-block');
        const dropdownMenu = dropdown.querySelector('.origin-top-right');

        dropdownMenu.style.display = 'none';

        dropdown.addEventListener('click', function() {
            if (dropdownMenu.style.display === 'none') {
                dropdownMenu.style.display = 'block';
            } else {
                dropdownMenu.style.display = 'none';
            }
        });
    });

    </script>

</body>
</html>
