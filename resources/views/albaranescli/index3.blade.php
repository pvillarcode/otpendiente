<!-- resources/views/albaranescli/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albaranescli</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Albaranescli</h1>

        <!-- Barra de búsqueda -->
        <div class="row">
            <div class="input-field col s12">
                <input id="search" type="text" class="validate">
                <label for="search">Buscar por código</label>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <table class="striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre Cliente</th>
                    <th>Observaciones</th>
                    <th>Corte</th>
                    <th>Pulido</th>
                    <th>Perforado</th>
                </tr>
            </thead>
            <tbody id="albaranescli-body">
                @foreach ($albaranescli as $albaran)
                    <tr>
                        <td>{{ $albaran->codigo }}</td>
                        <td>{{ $albaran->nombrecliente }}</td>
                        <td>{{ $albaran->observaciones }}</td>
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
                                <input type="checkbox" {{ $albaran->perforado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'perforado')"/>
                                <span></span>
                            </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('input', function() {
                const query = searchInput.value;
                if (query.length >= 3 || query.length == 0) {
                    fetch('/search-albaranescli?query=' + query)
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.getElementById('albaranescli-body');
                            tbody.innerHTML = '';
                            data.forEach(albaran => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${albaran.codigo}</td>
                                    <td>${albaran.nombrecliente}</td>
                                    <td>${albaran.observaciones}</td>
                                    <td>
                                        <label>
                                            <input type="checkbox" ${albaran.corte ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'corte')"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" ${albaran.pulido ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'pulido')"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" ${albaran.perforado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'perforado')"/>
                                            <span></span>
                                        </label>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        });
                }
            });
        });

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
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
