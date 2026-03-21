<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

        <title>{{ config('app.name', 'When and What') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="manifest" href="{{ url('manifest.json') }}" />

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>

        <!-- FontAwesome -->
        <script src="https://kit.fontawesome.com/d1f6020a28.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{ mix('css/bootstrap.css') }}">

        @stack('styles')

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>

        <nav class="navbar navbar-expand-md sticky-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="/dashboard">
                    <img src="{{ url('logo.png') }}" alt="" height="32">
                    <span class="navbar-brand-name">When and What</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    @php $routeName = Route::currentRouteName(); @endphp
                    <ul class="navbar-nav ms-auto align-items-md-center gap-md-1 mb-2 mb-md-0">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">Dashboard</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ (strpos($routeName, 'checkins') === 0 || strpos($routeName, 'locations') === 0) ? 'active' : '' }}"
                               href="#" id="navLocationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Locations
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navLocationDropdown">
                                <li><a class="dropdown-item" href="{{ route('checkins.index') }}">Checkins</a></li>
                                <li><a class="dropdown-item" href="{{ route('locations.map') }}">Map</a></li>
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}">Categories</a></li>
                            </ul>
                        </li>

                        @if(auth()->user() && in_array(auth()->user()->email, config('auth.admin_emails')))
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($routeName, 'notes') === 0 ? 'active' : '' }}" href="/notes">Journal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ strpos($routeName, 'podcasts') === 0 ? 'active' : '' }}" href="{{ route('podcasts.plays') }}">Podcasts</a>
                            </li>
                        @endif

                        <li class="nav-item dropdown ms-md-2">
                            <a class="nav-link dropdown-toggle" href="#" id="navProfileDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()?->name ?? 'Account' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navProfileDropdown">
                                <li><a href="/accounts" class="dropdown-item">Integrations</a></li>
                                <li><a href="/user/profile" class="dropdown-item">Profile</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}" class="dropdown-item"
                                           onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        @yield('full-content')

        <div class="container py-4">
            <div class="row">
                @yield('content')
            </div>
        </div>

        <footer class="site-footer">
            <div class="container">
                <div class="row align-items-center gy-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ url('logo.png') }}" alt="" height="24"
                                style="filter: brightness(0) invert(1); opacity: 0.85;">
                            <span class="footer-brand-name">When and What</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-center">
                        <nav class="d-flex flex-wrap gap-3 justify-content-md-center">
                            <a href="/dashboard">Dashboard</a>
                            <a href="{{ route('checkins.index') }}">Checkins</a>
                            <a href="{{ route('locations.map') }}">Locations</a>
                            <a href="/accounts">Integrations</a>
                        </nav>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span>&copy; {{ date('Y') }} When and What. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
