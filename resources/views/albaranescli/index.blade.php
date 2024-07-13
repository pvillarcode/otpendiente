@extends('layouts.app')

@section('title', 'Templados')

@section('additional_styles')
<style>
    /* Estilos específicos para templados */
</style>
@endsection

@section('content')
    @include('partials.search_bar')
    @include('partials.nav_tabs', ['active' => 'templados'])

    <div class="table-container">
        <table class="striped">
            <thead>
                <tr>
                    <th></th>
                    <th>OT</th>
                    <th class="table-client-column">Cliente</th>
                    <th>OBS</th>
                    <th>F. ING</th>
                    <th>F. COMP</th>
                    <th class="checkbox-column">CORT</th>
                    <th class="checkbox-column">PULI</th>
                    <th class="checkbox-column">PERF</th>
                    <th class="checkbox-column">PINT</th>
                    <th class="checkbox-column">EMP</th>
                    <th class="table-state-column">Nota</th>
                </tr>
            </thead>
            <tbody id="albaranescli-body">
                @foreach ($albaranescli as $albaran)
                <tr>                        
                        <td>
                            <label>
                                <input type="checkbox" class="select-all-row" data-codigo="{{ $albaran->codigo }}"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <span class="short-code">{{ substr($albaran->codigo, -4) }}</span>
                            <input type="hidden" value="{{ $albaran->codigo }}">
                        </td>
                        <td>
                            <a href="https://www.angelini.guiaideas.com/EditAlbaranCliente?code={{ $albaran->idalbaran }}" target="_blank">
                                <span class="text-desc">{{ $albaran->nombrecliente }}</span>
                            </a>
                        </td>
                        <td><span class="text-desc">{{ $albaran->observaciones }}</span></td>
                        <td>{{ $albaran->ingreso }}</td>
                        <td>{{ $albaran->compromiso }}</td>
                        <td>
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="corte" {{ $albaran->corte ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'corte')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="pulido" {{ $albaran->pulido ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pulido')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="perforado" {{ $albaran->perforado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'perforado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="pintado" {{ $albaran->pintado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pintado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="empavonado" {{ $albaran->empavonado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'empavonado')"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                        <input type="text" name="estado" value="{{ $albaran->estado }}"
                        onkeypress="updateEstado(event, this, '{{ $albaran->codigo }}')"
                        oninput="updateEstado(event, this, '{{ $albaran->codigo }}')"
                        onblur="updateEstado(event, this, '{{ $albaran->codigo }}')">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');

    let timeout = null;


    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = searchInput.value;
        timeout = setTimeout(function() {
            if (query.length >= 3 || query.length === 0) {
                fetch('/search-albaranescli?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('albaranescli-body');
                        tbody.innerHTML = '';
                        data.forEach(albaran => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>
                                    <label>
                                        <input type="checkbox" class="select-all-row" data-codigo="${albaran.codigo}"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="short-code">${albaran.codigo.slice(-4)}</span>
                                    <input type="hidden" value="${albaran.codigo}">
                                </td>
                                <td>
                                        <a href="https://www.angelini.guiaideas.com/EditAlbaranCliente?code=${albaran.idalbaran}" target="_blank">
                                            <span class="text-desc">${albaran.nombrecliente}</span>
                                        </a>
                                    </td>
                                <td><span class="text-desc">${albaran.observaciones}</span></td>
                                <td>${albaran.ingreso}</td>
                                <td>${albaran.compromiso}</td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="row-checkbox" data-columna="corte" ${albaran.corte ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'corte')"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="row-checkbox" data-columna="pulido" ${albaran.pulido ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'pulido')"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="row-checkbox" data-columna="perforado" ${albaran.perforado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'perforado')"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="row-checkbox" data-columna="pintado" ${albaran.pintado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'pintado')"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" class="row-checkbox" data-columna="empavonado" ${albaran.empavonado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'empavonado')"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="estado" value="${albaran.estado}"
                                    onkeypress="updateEstado(event, this, '${albaran.codigo}')"
                                    oninput="updateEstado(event, this, '${albaran.codigo}')"
                                    onblur="updateEstado(event, this, '${albaran.codigo}')">
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });

                        // Reattach event listeners to new checkboxes
                        const rowSelectors = document.querySelectorAll('.select-all-row');
                        rowSelectors.forEach(selector => {
                            const row = selector.closest('tr');
                            const checkboxes = row.querySelectorAll('.row-checkbox');
                            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                            selector.checked = allChecked;

                            selector.addEventListener('change', function() {
                                const checked = selector.checked;
                                checkboxes.forEach(checkbox => {
                                    checkbox.checked = checked;
                                });
                                updateAllCheckboxState(checked, selector.getAttribute('data-codigo'), checkboxes);
                            });
                        });
                    });
            }
        }, 1000); // Esperar 1 segundo antes de enviar la solicitud
    });
});
</script>
@endsection