<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0">
        <title>@yield('title') - PatriMag</title>

        @viteReactRefresh
        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        <link rel="stylesheet" href="{{ asset('css/easymde.min.css') }}">
        {!! NoCaptcha::renderJs() !!}

        <script async src="https://www.googletagmanager.com/gtag/js?id=G-69FMMRXPWT"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('app.gtag') }}');
        </script>
    </head>
    <body>
        @yield('header', view('components.header._header'))
        @yield('body')
        @yield('footer', view('components.footer._footer'))

        <div class="flashes-container">
            @if (Session::has('success'))
                @if (gettype(Session::get('success')) === 'string')
                    @include('components.flashes._flash', ['type' => 'success', 'message' => Session::get('success')])

                @else
                    @foreach (Session::get('success') as $success)
                        @include('components.flashes._flash', ['type' => 'success', 'message' => $success])
                    @endforeach
                @endif
            @endif
            @if (Session::has('error'))
                @if (gettype(Session::get('error')) === 'string')
                    @include('components.flashes._flash', ['type' => 'danger', 'message' => Session::get('error')])
                @else
                    @foreach (Session::get('error') as $error)
                        @include('components.flashes._flash', ['type' => 'danger', 'message' => $error])
                    @endforeach
                @endif
            @endif

            @php
                Session::forget(['success', 'error']);
            @endphp
        </div>
    </body>
</html>
