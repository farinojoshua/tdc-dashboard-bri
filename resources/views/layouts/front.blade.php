<!DOCTYPE html>
<html>
<head>
    @yield('title')

    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    {{-- create navbar --}}
    <div>
        <nav class="flex justify-between p-6 transition-transform duration-300 ease-out transform -translate-y-full bg-white shadow-lg font-poppins" id="navbar">
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
                </div>
                <a href="" class="text-xl font-bold text-dark-blue">Background Jobs</a>
            </div>
        </nav>
    </div>


    <div class="container mx-auto font-poppins">
         @yield('content')
    </div>

    <script>
        // make the navbar active when mouse hover to top of the page
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('navbar');
            navbar.classList.toggle('sticky', window.scrollY > 0);
        })
    </script>


    {{-- add script yield --}}
    @yield('script')

</body>
</html>
