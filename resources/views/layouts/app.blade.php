<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/style.css') }}" rel="stylesheet">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('index')}}">HR PHP</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ ($pageName == 'client.temperature.index') ? 'active': ''}}"><a href="{{ route('client.temperature.index') }}">Температура</a>
                    </li>
                    <li class="{{ ($pageName == 'admin.order.index') ? 'active': ''}}"><a href="{{ route('admin.order.index') }}">Заказы</a>
                    </li>
                    <li class="{{ ($pageName == 'admin.product.index') ? 'active': ''}}"><a href="{{ route('admin.product.index') }}">Продукты</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container page-content">
        @isset($title)
        <div class="page-header">
            <h1>{{ $title }}</h1>
        </div>
        @endisset
        @yield('content')
    </div>

</div>

<!-- Scripts -->
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/script.js') }}"></script>
</body>
</html>