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

    {{-- sweetalert2 --}}
    <script src="{{ asset('sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.min.css') }}">

    <style>
        .navbar-button {
            overflow: hidden;
            /* background-color: #333; */
            position: fixed;
            bottom: 0;
            width: 100%;
            background-image: linear-gradient(#BC8C64, #C1691B, #615F5D);
            /* background-attachment: fixed; */
        }

        body {
            background: url("{{ asset('images/background/6.jpg') }}") no-repeat center center fixed;
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
    </style>
    {{-- jquery --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
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
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/') }}">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="150px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                         <span style="color:black; font-weight: bold">{{ __('เดิมพัน') }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('user.home') }}">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="150px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                         <span style="color:black; font-weight: bold">{{ __('เติมเงิน') }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (!(Auth::guard('user')->check() || Auth::guard('admin')->check()))
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="box">
                                        <img src="{{ asset('images/btn/6.png') }}" width="150px"
                                            alt="{{ asset('images/btn/6.png') }}">
                                        <div class="text">
                                             <span style="color:black; font-weight: bold">{{ Auth::guard('user')->user()->name }}</span>
                                        </div>
                                    </div>
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <br>
    <br>
    <br>
    <br>
    {{-- ชื่อผู้ใช้ เงินคงเหลือ --}}
    @include('layouts.user.nav-bar-button')

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
