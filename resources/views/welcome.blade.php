<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPA Authentication using Laravel 9 Sanctum, Vue 3 and Vite - TechvBlogs</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

       <!-- Styles -->
       <link rel="stylesheet" href="{{ asset('css/app.css') }}">
          <!-- Scripts -->
          <script src="{{ asset('js/app.js') }}" defer></script>

    </head>
    <body>
        <div id="app">
            <router-view></router-view>
        </div>
    </body>
</html>
