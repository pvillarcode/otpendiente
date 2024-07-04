<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OT PENDIENTES</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!-- Material Design Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <style>
        .table-state-column {
            width: 20%;
        }
        .table-client-column {
            width: 15%;
        }
        .checkbox-column {
            text-align: center;
        }
        .btn.active {
            background-color: #26a69a;
            color: white;
        }

        .checkbox-column input[type="checkbox"] {
            margin: 0 auto;
        }
        nav {
  background-color: #616161; /* Gris medio para el fondo */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tabs {
  background-color: #616161; /* Mismo color que nav para consistencia */
}

.tabs .tab a {
  color: #e0e0e0; /* Gris muy claro para el texto */
  font-weight: 500;
}

.tabs .tab a:hover {
  background-color: #757575; /* Gris ligeramente más claro al pasar el mouse */
  color: #ffffff; /* Blanco para máximo contraste al hover */
}

.tabs .tab a.active {
  background-color: #9e9e9e; /* Gris más claro para la pestaña activa */
  color: #212121; /* Gris muy oscuro para el texto de la pestaña activa */
  font-weight: 600;
}

.tabs .indicator {
  background-color: #2196F3; /* Azul para el indicador */
  height: 3px;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>OT PENDIENTES</h1>

        <!-- Barra de búsqueda -->
        <div class="row">
            <div class="input-field col s12">
                <input id="search" type="text" class="validate">
                <label for="search">Buscar por código</label>
            </div>
        </div>
        <nav class="nav-extended">
        <div class="nav-wrapper">
            <ul class="tabs tabs-transparent">
            <li class="tab"><a href="/albaranescli">Templados</a></li>
            <li class="tab"><a href="/laminado" class="active">Laminados</a></li>
            </ul>
        </div>
        </nav>
        <div id="loading-icon" style="display: none;">
            <i class="material-icons" style="font-size: 48px;">refresh</i>
        </div>

        <!-- Tabla de resultados -->
        <table class="striped">
            <thead>
                <tr>
                    <th>OT</th>
                    <th class="table-client-column">Nombre Cliente</th>
                    <th>Observaciones</th>
                    <th>Ingreso</th>
                    <th>Compromiso</th>
                    <th class="checkbox-column">CORT</th>
                    <th class="checkbox-column">PULI</th>
                    <th class="checkbox-column">PINT</th>
                    <th class="checkbox-column">CURV</th>
                    <th class="checkbox-column">LAM</th>
                    <th class="table-state-column">Nota</th>
                </tr>
            </thead>
            <tbody id="albaranescli-body">
                @foreach ($albaranescli as $albaran)
                    <tr>
                        <td>
                            <span class="short-code">{{ substr($albaran->codigo, -4) }}</span>
                            <input type="hidden" value="{{ $albaran->codigo }}">
                        </td>
                        <td>{{ $albaran->nombrecliente }}</td>
                        <td>{{ $albaran->observaciones }}</td>
                        <td>{{ $albaran->ingreso }}</td>
                        <td>{{ $albaran->compromiso }}</td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $albaran->corte ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'corte')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $albaran->pulido ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pulido')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $albaran->pintado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pintado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $albaran->curvado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'curvado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $albaran->laminado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'laminado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="estado" value="{{ $albaran->estado }}" onkeypress="updateEstado(event, this, '{{ $albaran->codigo }}')">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>


        function updateCheckboxState(checkbox, codigo, columna) {
            const valor = checkbox.checked ? 1 : 0;

            fetch('/update-checkbox', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    codigo: codigo,
                    columna: columna,
                    valor: valor
                })
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(data => {
                console.log(data);
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function updateEstado(event, input, codigo) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission if it's within a form

                fetch('/update-estado', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        codigo: codigo,
                        estado: input.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optionally, provide some visual feedback
                        input.style.backgroundColor = '#e8f5e9';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                        }, 2000);
                    } else {
                        console.error('Error updating estado:', data.error);
                        // Optionally, provide some error feedback
                        input.style.backgroundColor = '#ffebee';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
