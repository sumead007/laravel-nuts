<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>J.Club</title>

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
            top: 20%;
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

    {{-- jquery --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>


</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent" id="nav">
            <div class="container">
                <a class="navbar-brand" href="{{ route('admin.home') }}">
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
                            <a class="nav-link" aria-current="page" href="{{ route('admin.home') }}">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="120px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                        <span style="color:black; font-weight: bold">{{ __('หน้าหลัก') }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('admin.accept.top_up.view') }}">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="120px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                        <span
                                            style="color:black; font-weight: bold; font-size:11.7px;">{{ __('ยืนยันเติมเงิน') }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="120px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                        <span
                                            style="color:black; font-weight: bold; font-size:11.7px;">{{ __('สมาชิก') }}</span>
                                    </div>
                                </div>

                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                    href="{{ route('admin.manage_user.view') }}">จัดการสมาชิก</a>
                                <a class="dropdown-item"
                                    href="{{ route('admin.manage_user.link_register.view') }}">ลิงค์สมัครสมาชิก</a>
                                @if (Auth::guard('admin')->user()->position == 0)
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.manage_agen.view') }}">จัดการข้อมูลเอเย่น</a>
                                @endif
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="box">
                                    <img src="{{ asset('images/btn/5.png') }}" width="120px"
                                        alt="{{ asset('images/btn/5.png') }}">
                                    <div class="text">
                                        <span style="color:black; font-weight: bold">{{ __('ธนาคาร') }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                    href="{{ route('admin.manage_bank.setting_my_bank.view') }}">ตั้งค่าธนาคารของฉัน</a>
                            </div>
                        </li>
                        @if (Auth::guard('admin')->user()->position == 0)
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page"
                                    href="{{ route('admin.clear_percent.view') }}">
                                    <div class="box">
                                        <img src="{{ asset('images/btn/5.png') }}" width="120px"
                                            alt="{{ asset('images/btn/5.png') }}">
                                        <div class="text">
                                            <span
                                                style="color:black; font-weight: bold">{{ __('เคลียร์ยอด') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endif
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
                                        <img src="{{ asset('images/btn/6.png') }}" width="120px"
                                            alt="{{ asset('images/btn/6.png') }}">
                                        <div class="text">
                                            <span style="color:black; font-weight: bold">
                                                {{ Auth::guard('admin')->user()->name }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.change_password.view') }}">
                                        {{ __('เปลี่ยนรหัสผ่าน') }}
                                    </a>

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
    {{-- select2 --}}
    <link href="{{ asset('select2/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('select2/js/select2.min.js') }}" defer></script>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
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
