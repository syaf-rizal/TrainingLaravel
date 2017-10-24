<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link href="{{ asset('/css/style.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <script type="text/javascript" src="{{ asset('/js/jquery.min.js') }}"></script>
    </head>
    <body>
        <div id="main" style="padding-left:0px;">
            <div class="wrapper">
                <section id="content">
                    <div class="container">
                    {!! $smartTable !!}
                    </div>
                </section>
            </div>
        </div>
    <script type="text/javascript" src="{{ asset('/js/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/moment-with-locales.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/newtable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
