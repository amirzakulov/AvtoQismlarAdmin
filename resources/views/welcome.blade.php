<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Avto Qismlar</title>
        <link rel="stylesheet" href="/public/css/all.css">
        <link rel="shortcut icon" type="icon" href="/public/favicon.ico">
        <script>
            (function (){
                window.Laravel = {csrfToken: '{{ csrf_token() }}'}
            })();
        </script>
    </head>
    <body class="antialiased">
        <div id="app">
            @if(Auth::check())
            <mainapp :user="{{Auth::user()}}" :permissions="{{Auth::user()->role->permission}}"></mainapp>
            @else
            <mainapp :user="false"></mainapp>
            @endif
        </div>
    </body>
    <script src="{{mix('/js/app.js')}}"></script>
</html>
