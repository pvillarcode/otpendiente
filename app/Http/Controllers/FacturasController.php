<?php

namespace App\Http\Controllers;
use App\Models\FacturaCli;
use App\Models\LineaFacturaCli;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturasController extends Controller
{
    public function index()
    {
        $facturascli = FacturaCli::select('codigo', 'cifnif', 'nombrecliente', 'neto', 'observaciones', 'total')
        ->orderBy('hora', 'desc')
        ->limit(50)
        ->get();
        return view('facturas.index', compact('facturascli'));
    }

    // En FacturasController

    public function detalleFactura($codigo = null)
    {
        $factura = null;
        $lineas = [];
        if (!is_null($codigo)) {
            $factura = FacturaCli::where('codigo', $codigo)->first();
            if ($factura) {
                $lineas = LineaFacturaCli::where('idfactura', $factura->idfactura)->get();
                $cliente = Cliente::where('codcliente', $factura->codcliente)->first();
            }
        }   
        return view('facturas.detalle',compact('factura', 'lineas','cliente'));
    }

    public function enviarFactura(Request $request)
    {
        $data = $request->all();
        $tipoDTE = (int) $request->input('tipo_dte', 33);  // 33 es el valor predeterminado si no se especifica
        $fmaPago = (int) $request->input('forma_pago', 1); 
        $detalles = [];
        $montoNeto = 0;

        if (isset($data['productos'])) {
            foreach ($data['productos'] as $index => $producto) {
                $detalles[] = [
                    "NroLinDet" => $index + 1,
                    "DscItem" => $producto['descripcion'] ?? '',
                    "NmbItem" => $producto['referencia'] ?? '',
                    "QtyItem" => $producto['cantidad'] ?? '0',
                    "UnmdItem" => 'un',
                    "PrcItem" => $producto['precio'] ?? '0',
                    "MontoItem" => strval(($producto['precio'] ?? 0) * ($producto['cantidad'] ?? 0))
                ];
            }
        }

        $montoNeto = (int) $request->input('monto_neto');
        $totalIva = (int) $request->input('total_iva');
        $montoTotal = (int) $request->input('monto_total');
       
        $tieneReferencias = isset($data['referencias']) && is_array($data['referencias']) && count($data['referencias']) > 0;
        $referencias = [];
    
        // Procesar referencias
        $tieneReferencias = false;
        $referencias = [];

        if (isset($data['referencias']) && is_array($data['referencias']) && count($data['referencias']) > 0) {
            foreach ($data['referencias'] as $referencia) {
                if (!empty($referencia['tipo_documento']) || !empty($referencia['folio_ref']) || !empty($referencia['fecha_ref']) || !empty($referencia['razon_referencia'])) {
                    $tieneReferencias = true;
                    $referencias[] = [
                        "codigoTipoDteReferencia" => (int)$referencia['tipo_documento'],
                        "folioReferencia" => $referencia['folio_ref'],
                        "fechaDteReferenciado" => $referencia['fecha_ref'],
                        "razonReferencia" => $referencia['razon_referencia']
                    ];
                }
            }
        }
        
        $iva = ceil($montoNeto * 0.19);
        // Monto total es la suma del monto neto más el IVA
        $montoTotal = $montoNeto + $iva;

        $jsonRequest = [
            "Documento" => [
                "Encabezado" => [
                    "IdDoc" => [
                        "TipoDTE" => $tipoDTE ?? 39,  // Corrección de la clave aquí
                        "FchEmis" => $data['fecha_emision'] ?? date('Y-m-d'),  // Corrección de la clave aquí
                        "FchVenc" => $data['fecha_vencimiento'] ?? date('Y-m-d'),  // Corrección de la clave aquí
                        "FmaPago" => $fmaPago ?? 1
                    ],
                    "Emisor" => [
                        "RUTEmisor" => "76269769-6",
                        "RznSocEmisor" => "Chilesystems",
                        "GiroEmisor" => "Desarrollo de software",
                        "DirOrigen" => "Calle 7 numero 3",
                        "CmnaOrigen" => "Santiago"
                    ],
                    "Receptor" => [
                        "RUTRecep" => $data['rut_recep'] ?? '',
                        "RznSocRecep" => $data['razon_social_recep'] ?? '',
                        "DirRecep" => $data['dir_recep'] ?? '',
                        "CmnaRecep" => $data['cmna_recep'] ?? '',
                        "CiudadRecep" => $data['ciudad_recep'] ?? '',
                        "CorreoRecep" => $data['correo_recep'] ?? '',
                    ],
                    "Totales" => [
                        "MntNeto" => strval((int)$montoNeto),
                        "IVA" => strval((int)$iva),
                        "MntTotal" => strval((int)$montoTotal)
                    ]
                ],
                "Detalle" => $detalles,  // Asumiendo que 'detalle' es un array de productos
            ],
            "Observaciones" => $data['observaciones'] ?? '',
            "Cajero" => $data['cajero'] ?? '',
            "TipoPago" => $data['tipo_pago'] ?? 'CONTADO'
        ];
        if ($tieneReferencias) {
            $jsonRequest['Documento']['Encabezado']['TieneReferencias'] = true;
            $jsonRequest['Documento']['Encabezado']['Referencias'] = $referencias;
        }
        $jsonOutput = json_encode($jsonRequest, JSON_PRETTY_PRINT);
        return response($jsonOutput, 200)->header('Content-Type', 'application/json');
        dd($jsonRequest);
        // Enviar el request a la API
        $response = Http::post('https://api.example.com/factura', $jsonRequest);

        return response()->json([
            'status' => $response->successful(),
            'data' => $response->json()
        ]);
    }

}
