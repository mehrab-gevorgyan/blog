<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/my.css?v=').time() }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <header class="h-[100px] bg-white py-[10px] px-[50px] mb-[20px] flex justify-between items-center shadow-sm">

        {{-- logo --}}
        <div class="w-[70px]">
            <a href="{{ route('posts.index') }}">
                <img src="{{ asset('img/forum.png') }}" alt="logo">
            </a>
        </div>

        {{-- navigation --}}
        <nav>
            <ul class="h-[100px] flex justify-start items-center">
                <li>
                    <a class="text-center inline-block mr-[20px]" href="">All Posts</a>
                </li>
                <li>
                    <a class="text-center inline-block mr-[20px]" href="">Our Users</a>
                </li>
            </ul>
        </nav>

        {{-- search, new post, register, logout --}}
        <div>
            <ul class="h-[100px] flex justify-between items-center">
                {{-- search form --}}
                <li class="text-center inline-block mr-[20px]">
                    <form action="{{ route('posts.index') }}" class="h-[40px]">
                        @csrf
                        <input name="search" class="shadow outline-none w-[250px] focus:shadow-md mr-[20px] px-[15px] py-[10px]" type="text" placeholder="Search..." value="{{ request('search') }}">
                        <input type="submit" class="w-[32px] border-none cursor-pointer h-[40px] bg-no-repeat" style="background: url('img/loupe.png') no-repeat 50%" value="">
                    </form>
                </li>

                {{-- registration --}}
                @guest
                    <li class="text-center inline-block mr-[20px]">
                        <a class="main-link" href="{{ route('reg.create') }}">Registration</a>
                    </li>
                @endguest

                {{-- logout, new post --}}
                @auth
                    {{-- new --}}
                    <li class="text-center inline-block mr-[20px]" style="background: #fff;">
                        <a class="main-link" href="{{ route('posts.create') }}">NEW</a>
                    </li>
                    {{-- logout --}}
                    <li class="text-center inline-block mr-[20px]">
                        <a class="main-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                    <script>
                        $('.auth-user-top .user-img').on('click', function() {
                            $('.auth-user-top ul').slideToggle();
                        });
                    </script>
                @endauth
            </ul>
        </div>
    </header>
    <div class="max-w-[1200px] px-[20px] my-0 mx-auto flex">
        @yield('content')
    </div>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
