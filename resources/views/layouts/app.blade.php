<!doctype html>

<head>
    <!-- ... --->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<!-- ... --->
<body onload="clearFlashMessage()">
    @yield('content')
</body>

<!-- js -->
@yield('script')
