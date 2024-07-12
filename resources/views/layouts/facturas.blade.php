<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FACTURAS')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    @yield('additional_styles')
    <style>
        /* Estilos compartidos aquí */
        @include('partials.shared_styles')
    </style>
</head>
<body>
    <div class="container">
        <div class="header-container">
            <h1>Facturas</h1>
            <img src="https://www.favitorr.cl/wp-content/uploads/logo-favitorr.png" alt="Favitorr Logo" class="logo">
        </div>

        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    @yield('scripts')
    <script>
        @include('partials.shared_scripts')
    </script>
</body>
</html>