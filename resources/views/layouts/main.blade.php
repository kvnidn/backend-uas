<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Pengambilan Kunci {{ $title }}</title>

        <main>
        </main>

    </head>
    <body>

        @include('partials/navbar')
        <div class="content-isi">
            @yield('isi')
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{asset('script/script.js')}}"></script>
    </body>
</html>
