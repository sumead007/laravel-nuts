<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- jquery --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    {{-- sweetalert2 --}}
    <script src="{{ asset('sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.min.css') }}">
    <style>
        body {
            background: url("{{ asset('images/background/2.jpg') }}") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .box {
            position: relative;
            display: inline-block;
            /* Make the width of box same as image */
        }

        .box .text {
            position: absolute;
            z-index: 999;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 30%;
            /* Adjust this value to move the positioned div up and down */
            text-align: center;
            width: 60%;
            /* Set the width of the positioned div */
        }

        .box:hover {
            -ms-transform: scale(1.1);
            /* IE 9 */
            -webkit-transform: scale(1.1);
            /* Safari 3-8 */
            transform: scale(1.1);
        }
        

    </style>

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent" id="nav">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    J.Club
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (!(Auth::guard('user')->check() || Auth::guard('admin')->check()))
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <div class="box">
                                            <img src="{{ asset('images/btn/5.png') }}" width="150px"
                                                alt="{{ asset('images/btn/5.png') }}">
                                            <div class="text">
                                                <span
                                                    style="color:black; font-weight: bold">{{ __('เข้าสู่ระบบ') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{-- {{ Auth::guard('user')->user()->name }} --}}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('ออกจากระบบ') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 d-flex flex-column min-vh-100 justify-content-center align-items-center">
            @yield('content')
        </main>
    </div>
    <script>
        $(function() {
            //caches a jQuery object containing the header element
            var header = $('#nav');
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                if (scroll >= header.height()) {
                    header.fadeOut();
                } else {
                    header.fadeIn();
                }
            });
        });
    </script>
</body>

</html>
