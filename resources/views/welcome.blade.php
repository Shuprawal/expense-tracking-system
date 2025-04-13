<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Landing</title>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class=" bg-gray-800  text-gray-900 dark:bg-gray-900 dark:text-white">


<header class="bg-black ">
    <div class="px-3 py-3 d-flex justify-content-between align-items-center">
        <div class=" fw-bold text-danger">
            <img src="{{asset('image/mainLogo.png')}}" style="height: 50px">
        </div>

        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light text-dark">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</header>


{{--<section class="dark:bg-gray-800 bg-black py-20">--}}
{{--    <div class="container px-6 flex items-center justify-center space-x-8">--}}
{{--        <!-- Image Section -->--}}
{{--        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="w-1/2 h-auto object-contain">--}}

{{--        <!-- Text Section -->--}}
{{--        <div class="col-lg-6 text-white">--}}
{{--            <h2 class="display-3 fw-bold mb-4" style="font-family: 'Montserrat', sans-serif;">--}}
{{--                Track Every Penny,<br> Master Your Finances--}}
{{--            </h2>--}}
{{--            <p class="fs-4" style="font-family: 'Montserrat', sans-serif;">--}}
{{--                Stay on top of your financial goals with powerful tracking tools, ensuring every dollar is accounted for.--}}
{{--            </p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}


<section class=" dark:bg-gray-800 bg-black">
    <div class="container px-6 flex items-center justify-center space-x-5">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="" style="width: 100vh; height: 79vh">
        <div class="col-lg-6 text-white">
            <h2 class="display-3 fw-bold mb-4" style="font-family: 'Montserrat', sans-serif;">
                Track Every Penny,<br> Master Your Finances
            </h2>
            <p class="fs-4 " style="font-family: 'Montserrat', sans-serif;">
                Stay on top of your financial goals with powerful tracking tools, ensuring every dollar is accounted for.
            </p>
        </div>
    </div>
</section>


<section class="py-24 text-center bg-gradient-to-br from-red-600 to-pink-500 text-white">
    <div class="max-w-4xl mx-auto px-6">
        <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-black font-semibold rounded-lg shadow hover:bg-gray-100 transition">
            Get Started
        </a>
    </div>
</section>

<footer class="text-center py-6 bg-gray-900 text-sm text-gray-500 dark:text-gray-400">

</footer>

</body>
</html>
