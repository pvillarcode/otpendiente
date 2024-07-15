@extends('layouts.app')

@section('title', 'DESACTIVAR TEMPLADOS')

@section('header-title', 'DESACTIVAR ETAPAS TEMPLADOS')

@section('content')
    <table class="striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre Cliente</th>
                <th>Observaciones</th>
                <th class="checkbox-column">MATR</th>
                <th class="checkbox-column">CORT</th>
                <th class="checkbox-column">PULI</th>
                <th class="checkbox-column">PINT</th>
                <th class="checkbox-column">CURV</th>
                <th class="checkbox-column">LAM</th>
            </tr>
        </thead>
        <tbody id="albaranescli-body">
            @foreach ($albaranescli as $albaran)
                <tr>
                    <td>
                        <span class="short-code">{{ substr($albaran->codigo, -4) }}</span>
                        <input type="hidden" value="{{ $albaran->codigo }}">
                    </td>
                    <td>
                        <a href="https://www.angelini.guiaideas.com/EditAlbaranCliente?code={{ $albaran->idalbaran }}" target="_blank">
                            <span class="text-desc">{{ $albaran->nombrecliente }}</span>
                        </a>
                    </td>
                    <td>{{ $albaran->observaciones }}</td>
                    <td>
                        <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_matriz" {{ $albaran->disabled_matriz ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_matriz')"/>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_corte" {{ $albaran->disabled_corte ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_corte')"/>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_pulido" {{ $albaran->disabled_pulido ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_pulido')"/>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_pintado" {{ $albaran->disabled_pintado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_pintado')"/>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_curvado" {{ $albaran->disabled_curvado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_curvado')"/>
                            <span></span>
                        </label>
                    </td>
                    <td>
                    <label>
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_laminado" {{ $albaran->disabled_laminado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_laminado')"/>
                            <span></span>
                        </label>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection