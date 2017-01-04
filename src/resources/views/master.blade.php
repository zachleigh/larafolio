<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            @yield('title')
        </title>
        <script>
            window.Larafolio = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

        <link href="https://fonts.googleapis.com/css?family=Nova+Script" rel="stylesheet"> 
        
        <link rel="stylesheet" href="{{ manager_cache_bust('vendor/larafolio/css/larafolio-final.css') }}">
    </head>
    <body>
        <div id="app">
            <div class="container">
                @include('larafolio::components.flash-message')
                @include('larafolio::layout.nav')
                @yield('content')
                @include('larafolio::layout.footer')
            </div>
        </div>
        <script async src="{{ manager_cache_bust('vendor/larafolio/js/larafolio.js') }}" type="text/javascript"></script>
    </body>
</html>
