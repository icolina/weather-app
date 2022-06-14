<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <meta name="description" content="Test">
        @yield('meta')

        @stack('before-styles')
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @stack('after-styles')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body class="">
        <main role="main">
            @yield('content')
        </main>

        @stack('before-scripts')
        <script src="{{ asset('js/app.js') }}"></script>
        @stack('after-scripts')
    </body>
</html>
