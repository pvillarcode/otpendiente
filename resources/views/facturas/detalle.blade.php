@extends('layouts.facturas')

@section('content')
<div class="container">
    <h4>{{ $factura->codigo }}</h4>
    <form class="col s12" action="{{ route('enviar.factura') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Sección de detalles de factura -->
            <div class="input-field col s6">
                <input id="formato" type="text" class="validate" name="formato" value="Carta">
                <label for="formato">Formato</label>
            </div>
            <div class="input-field col s6">
            <select name="tipo_dte" id="tipo_dte" class="validate">
                    <option value="33" {{ old('tipo_dte', '33') == '33' ? 'selected' : '' }}>Factura Electrónica</option>
                    <option value="39" {{ old('tipo_dte', '33') == '39' ? 'selected' : '' }}>Boleta Electrónica</option>
                    <option value="52" {{ old('tipo_dte', '33') == '52' ? 'selected' : '' }}>Guía de Despacho</option>
                    <option value="61" {{ old('tipo_dte', '33') == '61' ? 'selected' : '' }}>Nota de Crédito Electrónica</option>
                </select>
                <label for="tipo_dte">Tipo DTE</label>
            </div>
            <div class="input-field col s6">
                <select name="forma_pago" id="forma_pago" class="validate">
                    <option value="" disabled {{ old('forma_pago') ? '' : 'selected' }}>Elige una opción</option>
                    <option value="1" {{ old('forma_pago') == '1' ? 'selected' : '' }}>Contado</option>
                    <option value="2" {{ old('forma_pago') == '2' ? 'selected' : '' }}>Crédito</option>
                </select>
                <label>Forma de Pago</label>
            </div>
            <div class="input-field col s6">
                <input type="text" class="datepicker" name="fecha_emision" id="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}">
                <label for="fecha_emision">Fecha de Emisión</label>
            </div>
            <div class="input-field col s6">
                <input id="dias_vencimiento" type="number" class="validate" name="dias_vencimiento" value="0">
                <label for="dias_vencimiento">Días de Vencimiento</label>
            </div>
            <div class="input-field col s6">
                <input id="descuento_global" type="number" class="validate" name="descuento_global" value="0">
                <label for="descuento_global">Descuento Global (Monto)</label>
            </div>
            <div class="input-field col s12">
                <label>
                    <input type="checkbox" name="iva_terceros"/>
                    <span>Iva Terceros</span>
                </label>
            </div>
        </div>

        <!-- Sección de cliente -->
        <div class="row">
            <div class="input-field col s6">
                <input id="rut_cliente" type="text" class="validate" name="rut_recep" value="{{ $cliente->cifnif ?? '' }}">
                <label for="rut_cliente" class="{{ $cliente ? 'active' : '' }}">Rut Cliente</label>
            </div>
            <div class="input-field col s6">
                <input id="razon_social" type="text" class="validate" name="razon_social_recep" value="{{ $cliente->razonsocial ?? '' }}">
                <label for="razon_social" class="{{ $cliente ? 'active' : '' }}">Razon Social</label>
            </div>
            <div class="input-field col s6">
                <input id="direccion" type="text" class="validate" name="dir_recep" value="{{ $cliente->direccion ?? '' }}">
                <label for="direccion" class="{{ $cliente ? 'active' : '' }}">Dirección</label>
            </div>
            <div class="input-field col s6">
                <input id="comuna" type="text" class="validate" name="cmna_recep" value="{{ $cliente->comuna ?? '' }}">
                <label for="comuna" class="{{ $cliente ? 'active' : '' }}">Comuna</label>
            </div>
            <div class="input-field col s6">
                <input id="ciudad" type="text" class="validate" name="ciudad_recep" value="{{ $cliente->ciudad ?? '' }}">
                <label for="ciudad" class="{{ $cliente ? 'active' : '' }}">Ciudad</label>
            </div>
            <div class="input-field col s6">
                <input id="correo_electronico" type="email" class="validate" name="correo_recep" value="{{ $cliente->email ?? '' }}">
                <label for="correo_electronico" class="{{ $cliente ? 'active' : '' }}">Correo Electrónico</label>
            </div>
        </div>

        <!-- Tabla de productos -->


        <table class="highlight">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>% Dto.</th>
                    <th>Neto</th>
                </tr>
            </thead>
            <tbody id="productoContainer">
                @forelse ($lineas as $index => $linea)
                    <tr>
                        <td><input type="text" name="productos[{{ $index }}][referencia]" value="{{ $linea->referencia }}" class="validate"></td>
                        <td><input type="text" name="productos[{{ $index }}][descripcion]" value="{{ $linea->descripcion }}" class="validate"></td>
                        <td><input type="number" name="productos[{{ $index }}][cantidad]" value="{{ $linea->cantidad }}" class="validate" oninput="calcularNeto(this)"></td>
                        <td><input type="number" name="productos[{{ $index }}][precio]" value="{{ $linea->pvpunitario }}" class="validate" oninput="calcularNeto(this)"></td>
                        <td><input type="number" name="productos[{{ $index }}][descuento]" value="{{ $linea->descuento }}" class="validate" oninput="calcularNeto(this)"></td>
                        <td><input type="number" name="productos[{{ $index }}][neto]" value="{{ $linea->pvptotal }}" class="validate" readonly></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay productos añadidos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
              <!-- Sección de Referencias -->
              <div class="row">
            <div class="col s12">
                <h5>Referencias</h5>
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>Tipo de Documento</th>
                            <th>Folio Ref</th>
                            <th>Fecha Ref</th>
                            <th>Razón Referencia</th>
                        </tr>
                    </thead>
                    <tbody id="referenciaContainer">
                        <tr>
                            <td>
                                <select name="referencias[0][tipo_documento]" class="validate">
                                    <option value="" disabled selected>Elige una opción</option>
                                    <option value="802">Nota de pedido</option>
                                    <option value="801">Orden de Compra</option>
                                </select>
                            </td>
                            <td><input type="text" name="referencias[0][folio_ref]" class="validate"></td>
                            <td><input type="text" class="datepicker" name="referencias[0][fecha_ref]" class="validate"></td>
                            <td><input type="text" name="referencias[0][razon_referencia]" class="validate"></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" onclick="agregarReferencia()" class="btn waves-effect waves-light">Agregar Referencia</button>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h5>Resumen</h5>
                <ul>
                    <li>Monto Neto: <span id="montoNeto"> {{ $factura->neto }} </span></li>
                    <li>Total IVA: <span id="totalIva"> {{ $factura->totaliva }} </span></li>
                    <li>Monto Total: <span id="montoTotal"> {{ $factura->total }}</span></li>
                </ul>
            </div>
        </div>  
        <input type="hidden" name="monto_neto" value="{{ $factura->neto }}">
        <input type="hidden" name="total_iva" value="{{ $factura->totaliva }}">
        <input type="hidden" name="monto_total" value="{{ $factura->total }}">
        <input type="hidden" name="codigo" value="{{ $factura->codigo }}">
        <div class="row">
            <div class="input-field col s12">
                <button type="submit" class="btn waves-effect waves-light">Guardar Factura</button>
            </div>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
    var dateElems = document.querySelectorAll('.datepicker');
    var dateInstances = M.Datepicker.init(dateElems, { format: 'yyyy-mm-dd' });

    //agregarProducto(); // Agrega una fila inicial al cargar la página
});

function agregarProducto() {
    const container = document.getElementById('productoContainer');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="referencia[]" placeholder="Referencia" class="validate"></td>
        <td><input type="text" name="descripcion[]" placeholder="Descripción" class="validate"></td>
        <td><input type="number" name="cantidad[]" placeholder="Cantidad" class="validate" oninput="calcularNeto(this)"></td>
        <td><input type="number" name="precio[]" placeholder="Precio" class="validate" oninput="calcularNeto(this)"></td>
        <td><input type="number" name="descuento[]" placeholder="% Dto." class="validate" oninput="calcularNeto(this)"></td>
        <td><input type="number" name="neto[]" placeholder="Neto" class="validate" readonly></td>
        <td><input type="number" name="impuesto[]" placeholder="% Impuesto" class="validate" value="19"></td>
    `;
    container.appendChild(row);
}

function calcularNeto(input) {
    const tr = input.parentElement.parentElement;
    const cantidad = parseFloat(tr.querySelector('input[name="cantidad[]"]').value) || 0;
    const precio = parseFloat(tr.querySelector('input[name="precio[]"]').value) || 0;
    const descuento = parseFloat(tr.querySelector('input[name="descuento[]"]').value) || 0;
    const neto = (cantidad * precio) - (cantidad * precio * descuento / 100);
    tr.querySelector('input[name="neto[]"]').value = neto.toFixed(2);
}

function agregarReferencia() {
    const container = document.getElementById('referenciaContainer');
    const index = container.children.length;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="referencias[${index}][tipo_documento]" class="validate">
                <option value="" disabled selected>Elige una opción</option>
                <option value="802">Nota de pedido</option>
                <option value="801">Orden de Compra</option>
            </select>
        </td>
        <td><input type="text" name="referencias[${index}][folio_ref]" class="validate"></td>
        <td><input type="text" class="datepicker" name="referencias[${index}][fecha_ref]" class="validate"></td>
        <td><input type="text" name="referencias[${index}][razon_referencia]" class="validate"></td>
    `;
    container.appendChild(row);
    M.FormSelect.init(row.querySelectorAll('select'));
    M.Datepicker.init(row.querySelectorAll('.datepicker'), {
        format: 'yyyy-mm-dd'
    });
}

</script>
@endsection
