@extends('layouts.facturas')

@section('content')
    <div class="table-container">
        <table class="striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Rut</th>
                    <th>Nombre Cliente</th>
                    <th>Observaciones</th>
                    <th>Total</th>
                    <th>Generar Factura</th>
                </tr>
            </thead>
            <tbody id="facturascli-body">
                @foreach($facturascli as $factura)
                    <tr>
                        <td>{{ $factura->codigo }}</td>
                        <td>{{ $factura->cifnif }}</td>
                        <td class="text-desc">{{ $factura->nombrecliente }}</td>
                        <td class="text-desc">{{ $factura->observaciones }}</td>
                        <td>{{ $factura->total }}</td>
                        <td>
                            <a href="/facturas/detalle/{{ $factura->codigo }}" class="btn btn-primary">Facturar</a>
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
            fetch(`/search-facturas?query=${query}&category=templados`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('facturascli-body');
                    tbody.innerHTML = '';
                    data.forEach(factura => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${factura.codigo}</td>
                            <td>${factura.cifnif}</td>
                            <td>${factura.nombrecliente}</td>
                            <td>${factura.observaciones}</td>
                            <td>${factura.total}</td>
                            <td>
                                <a href="/facturas/detalle/${factura.codigo}" class="btn btn-primary">Generar Factura</a>
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
