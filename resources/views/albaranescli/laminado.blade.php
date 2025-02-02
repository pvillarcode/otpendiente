@extends('layouts.app')

@section('title', 'Laminados')
@section('body-class', 'index-view')

@section('additional_styles')
<style>
    /* Estilos específicos para laminados */
</style>
@endsection

@section('content')
    @include('partials.search_bar')
    @include('partials.nav_tabs', ['active' => 'laminados'])

    <div class="table-container">
        <table class="striped">
            <thead>
                <tr>
                    <th></th>
                    <th>OT</th>
                    <th class="table-client-column">Cliente</th>
                    <th>OBS</th>
                    <th>F. ING</th>
                    <th>F. COMP</th>
                    <th class="checkbox-column">MATR</th>
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
                        @if(!$albaran->disabled_matriz)
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="matriz" {{ $albaran->matriz ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'matriz')"/>
                                <span></span>
                            </label>
                            @endif
                        </td>
                        <td>
                        @if(!$albaran->disabled_corte)
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="corte"  {{ $albaran->corte ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'corte')"/>
                                <span></span>
                            </label>
                            @endif
                        </td>
                        <td>
                        @if(!$albaran->disabled_pulido)
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="pulido"  {{ $albaran->pulido ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pulido')"/>
                                <span></span>
                            </label>@endif
                        </td>
                        <td>
                        @if(!$albaran->disabled_pintado)
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="pintado"  {{ $albaran->pintado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'pintado')"/>
                                <span></span>
                            </label>@endif
                        </td>
                        <td>
                        @if(!$albaran->disabled_curvado)
                            <label>
                                <input type="checkbox" class="row-checkbox" data-columna="curvado"  {{ $albaran->curvado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'curvado')"/>
                                <span></span>
                            </label>@endif
                        </td>
                        <td>
                        @if(!$albaran->disabled_laminado)
                            <label>
                                <input type="checkbox"  class="row-checkbox" data-columna="laminado"  {{ $albaran->laminado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'laminado')"/>
                                <span></span>
                            </label>@endif
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
        clearTimeout(timeout); // Limpiar el temporizador anterior

        const query = searchInput.value;
        const category = 'laminados';

        timeout = setTimeout(function() {
            if (query.length >= 3 || query.length === 0) {
                fetch(`/search-albaranescli?query=${query}&category=${category}`)
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
                                ${generateCheckboxColumn(albaran, 'matriz')}
                                ${generateCheckboxColumn(albaran, 'corte')}
                                ${generateCheckboxColumn(albaran, 'pulido')}
                                ${generateCheckboxColumn(albaran, 'pintado')}
                                ${generateCheckboxColumn(albaran, 'curvado')}
                                ${generateCheckboxColumn(albaran, 'laminado')}
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
                        attachRowSelectors();
                    });
            }
        }, 1000); // Esperar 1 segundo antes de enviar la solicitud
    });

    function generateCheckboxColumn(albaran, column) {
        const isIndexView = document.body.classList.contains('index-view');
        const checkboxDisplay = isIndexView && albaran[`disabled_${column}`] ? 'none' : '';

        return `
            <td>
                <label style="display: ${checkboxDisplay};">
                    <input type="checkbox" class="row-checkbox" data-columna="${column}" ${albaran[column] ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', '${column}')"/>
                    <span></span>
                </label>
            </td>
        `;
    }

    function attachRowSelectors() {
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
    }

    // Initial attachment of event listeners
    attachRowSelectors();
});
</script>
@endsection
