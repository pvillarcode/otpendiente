<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Listado de Órdenes</title>

    <!-- Materialize CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            padding-top: 3rem;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .card {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="center-align">Listado de Albaranescli</h1>

        <div class="row">
            @foreach ($albaranescli as $albaran)
            <div class="col s12 m6">
                <div class="card blue-grey darken-1 white-text">
                    <div class="card-content">
                        <span class="card-title">{{ $albaran->codigo }}</span>
                        <p>Nombre Cliente: {{ $albaran->nombrecliente }}</p>
                        <p>Observaciones: {{ $albaran->observaciones }}</p>
                    </div>
                    <div class="card-action">
                        <label>
                            <input type="checkbox" class="filled-in" data-codigo="{{ $albaran->codigo }}" data-columna="corte" {{ $albaran->corte ? 'checked' : '' }}>
                            <span>Corte</span>
                        </label>
                        <label>
                            <input type="checkbox" class="filled-in" data-codigo="{{ $albaran->codigo }}" data-columna="pulido" {{ $albaran->pulido ? 'checked' : '' }}>
                            <span>Pulido</span>
                        </label>
                        <label>
                            <input type="checkbox" class="filled-in" data-codigo="{{ $albaran->codigo }}" data-columna="perforado" {{ $albaran->perforado ? 'checked' : '' }}>
                            <span>Perforado</span>
                        </label>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Materialize JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Evento para actualizar el estado del checkbox en la base de datos
            $('.filled-in').change(function() {
                var checkbox = $(this);
                var codigo = checkbox.data('codigo');
                var columna = checkbox.data('columna');
                var valor = checkbox.prop('checked') ? 1 : 0;

                // AJAX para enviar los datos al servidor
                $.ajax({
                    url: '{{ route("checkbox.update") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        codigo: codigo,
                        columna: columna,
                        valor: valor
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
