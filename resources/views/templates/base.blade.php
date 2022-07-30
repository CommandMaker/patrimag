<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0">
        <title>@yield('title') - PatriMag</title>

        <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="/css/easymde.min.css">
        {!! NoCaptcha::renderJs() !!}
    </head>
    <body>
        @yield('header', view('components.header._header'))
        @yield('body')
        @yield('footer', view('components.footer._footer'))

        <script src="/js/app.js"></script>
    </body>
</html>
