<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? '' }}</title>
        <!-- Favicon -->
        <link href="{{ asset('assets') }}/img/brand/favicon.ico" rel="icon" type="image/ico">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        <!-- custom CSS -->
        <link type="text/css" href="{{ asset('') }}css/app.css" rel="stylesheet">
        @stack('css')
    </head>
    <body class="{{ $class ?? '' }}">
        @include('layouts.partials.notifications')
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @if (!isset($hasSidebar) || $hasSidebar)
                @include('layouts.navbars.sidebar')
            @endif
        @endauth
        
        <div class="main-content">    
            @include('layouts.navbars.navbar')
            
            @yield('content')
        </div>

        @guest()
            @include('layouts.footers.guest')
        @endguest

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        
        @stack('js')
        
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>

        @stack('script')

        <div id="feedback-report-btn">
            <a href="https://github.com/HE-Arc/Caravel/issues/new?labels=report" title="Report an issue">
                <i class="fas fa-bug"></i>
            </a>
        </div>
    </body>
</html>