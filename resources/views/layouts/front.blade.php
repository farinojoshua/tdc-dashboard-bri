<!DOCTYPE html>
<html>
<head>
    @yield('title')

    <!-- Fonts -->
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

    <style>
        #navbar {
            position: absolute; /* Keeps the navbar at the top */
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 8px solid #DDE5E9;
            background-color: white; /* Optional: to ensure navbar background is not transparent */
        }

        .navbar-link {
            margin: 0 15px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar-link:hover {
            color: #2B4CDE;
        }

    </style>

</head>
<body>
    <div>
        <nav class="flex justify-between p-6 bg-white font-poppins" id="navbar">
            <div>
                <a href="" class="text-3xl font-semibold">
                    <span class="text-blue-600">TDC</span>Dashboard.
                </a>
            </div>
            <div class="relative flex gap-5">
                <a href="{{ route('user-management.request-by-type') }}" class="text-xl font-bold navbar-link text-dark-blue">Usman</a>
                <a href="{{ route('brisol.service-ci') }}" class="text-xl font-bold navbar-link text-dark-blue">Brisol</a>
                <div class="relative inline-block text-left">
                    <a href="{{ route('deployments.calendar') }}" class="inline-flex justify-center w-full text-xl font-bold navbar-link text-dark-blue">
                        Deployment
                    </a>
                </div>
                <a href="{{ route('background-jobs-monitoring.daily') }}" class="text-xl font-bold navbar-link text-dark-blue">Background Jobs</a>
                <a href="{{ route('login') }}" class="text-xl font-bold navbar-link text-dark-blue">Admin</a>
            </div>
        </nav>
    </div>

    <div class="px-10 mx-auto font-poppins" style="margin-top: 130px">
         @yield('content')
    </div>

    @yield('script')

</body>
</html>
