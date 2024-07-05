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
                            <span class="short-code">{{ substr($albaran->codigo, -4) }}</span>
                            <input type="hidden" value="{{ $albaran->codigo }}">
                        </td>
                        <td><span class="text-desc">{{ $albaran->nombrecliente }}</span></td>
                        <td><span class="text-desc">{{ $albaran->observaciones }}</span></td>
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
                                <input type="checkbox" {{ $albaran->perforado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'perforado')"/>
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
                                <input type="checkbox" {{ $albaran->empavonado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'empavonado')"/>
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
            searchInput.addEventListener('input', function() {
                const query = searchInput.value;
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
                                        <span class="short-code">${albaran.codigo.slice(-4)}</span>
                                        <input type="hidden" value="${albaran.codigo}">
                                    </td>
                                    <td><span class="text-desc">${albaran.nombrecliente}</span></td>
                                    <td><span class="text-desc">${albaran.observaciones}</span></td>
                                    <td>${albaran.ingreso}</td>
                                    <td>${albaran.compromiso}</td>
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
                                    <td>
                                        <label>
                                            <input type="checkbox" ${albaran.pintado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.codigo}', 'pintado')"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" ${albaran.empavonado ? 'checked' : ''} onchange="updateCheckboxState(this, '${albaran.empavonado}', 'pintado')"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" name="estado" value="${albaran.estado}"
                                        onkeypress="updateEstado(event, this, '${albaran.estado}')"
                                        oninput="updateEstado(event, this, '${albaran.estado}')"
                                        onblur="updateEstado(event, this, '${albaran.estado}')">
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        });
                }
            });
        });
</script>
@endsection