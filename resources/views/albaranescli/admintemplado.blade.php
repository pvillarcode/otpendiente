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
                <th class="checkbox-column">CORT</th>
                <th class="checkbox-column">PULI</th>
                <th class="checkbox-column">PERF</th>
                <th class="checkbox-column">PINT</th>
                <th class="checkbox-column">EMP</th>
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
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_perforado" {{ $albaran->disabled_perforado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_perforado')"/>
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
                            <input type="checkbox" class="row-checkbox" data-columna="disabled_empavonado" {{ $albaran->disabled_empavonado ? 'checked' : '' }} onchange="updateCheckboxState(this, '{{ $albaran->codigo }}', 'disabled_empavonado')"/>
                            <span></span>
                        </label>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
