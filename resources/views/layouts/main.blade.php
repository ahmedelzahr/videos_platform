<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Noto+Kufi+Arabic:wght@100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', __('Hasoup Video'))</title>
</head>

<body dir={{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ __('Hasoup Video') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav {{ App::getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} mb-2 items-center ">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fa-solid fa-house"></i>
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            {{ __('History') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('videos.create') }}">
                            <i class="fa-solid fa-upload"></i>
                            {{ __('Upload Video') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fa-solid fa-circle-play"></i>
                            {{ __('My Videos') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fa-solid fa-tv"></i>
                            {{ __('Chanels') }}
                        </a>
                    </li>
                </ul>
                @guest
                    <ul class="navbar-nav {{ App::getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} mb-2 items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">

                                {{ __('Sign In') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">

                                {{ __('Register') }}
                            </a>
                        </li>
                    </ul>
                @endguest
                @auth
                    <ul class="navbar-nav {{ App::getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }} mb-2 items-center ">
                        <li class="nav-item  dropdown">
                            <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                                    class="rounded-circle w-12" alt="...">
                            </a>
                            <ul
                                class="dropdown-menu {{ App::getLocale() == 'ar' ? 'dropdown-menu-start' : 'dropdown-menu-end' }} w-72">
                                <div
                                    class="d-flex flex-column {{ App::getLocale() == 'ar' ? 'align-items-start' : 'align-items-end' }} ">
                                    <li>
                                        <div class="flex items-center px-4 {{ App::getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                <div class="shrink-0 ">
                                                    <img class="size-10 rounded-full object-cover"
                                                        src="{{ Auth::user()->profile_photo_url }}"
                                                        alt="{{ Auth::user()->name }}" />
                                                </div>
                                            @endif

                                            <div>
                                                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}
                                                </div>
                                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li> <x-responsive-nav-link href="  {{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                            {{ __('Profile') }}
                                        </x-responsive-nav-link></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf
                                            <x-responsive-nav-link href="{{ route('logout') }}"
                                                @click.prevent="$root.submit();">
                                                {{ __('Log Out') }}
                                            </x-responsive-nav-link>
                                        </form>
                                    </li>
                                </div>

                            </ul>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>
    <main class="py-4">
        @if (Session::has('success'))
            <div class="alret alert-sucess" role="alert">
                    {{ Session::get('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    @livewireScripts
</body>
@yield('script')

</html>
