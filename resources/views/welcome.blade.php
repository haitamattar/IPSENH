<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{{ asset('images/apple-touch-icon.png') }}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{{ asset('images/favicon-32x32.png') }}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{{ asset('images/favicon-16x16.png') }}}">
    <link rel="manifest" href="{{{ asset('images/site.webmanifest') }}}">
    <link rel="mask-icon" href="{{{ asset('images/safari-pinned-tab.svg') }}}" color="#0041c7">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#0041c7">


    <title>Su user</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
</head>

<body>
    @if (Request::is('/'))
    <nav class="navbar navbar-expand-lg navbar-light bg-custom">
        <a class="navbar-brand" href="#">$su user</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto w-100 justify-content-end">
                <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="#showUserVistors">Features</a>
                <a class="nav-item nav-link" href="/search">Explore</a>
                <a class="nav-item nav-link" href="/login">Sign up</a>
            </div>
        </div>
    </nav>
    @endif

    <div id="app">
        <app></app>
    </div>

    <script async src="{{mix('js/app.js')}}"></script>

</body>

</html>
<script>
        @if(session('loggedIn'))
        var baseState = {
            loggedIn: {{ session('loggedIn') }},
            token: '{{ session('token') }}',
            baseUrl: '{{ URL('/') }}',
        }
        @else
        var baseState = {
            loggedIn: {{ $loggedIn }},
            token: '{{ $token }}',
            baseUrl: '{{ URL('/') }}',
        }
        @endif

</script>
