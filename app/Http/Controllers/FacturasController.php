<?php

namespace App\Http\Controllers;
use App\Models\FacturaCli;
use App\Models\LineaFacturaCli;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Response;

class FacturasController extends Controller
{
    public function index()
    {
        $facturascli = FacturaCli::select('codigo', 'cifnif', 'nombrecliente', 'neto', 'observaciones', 'total')
        ->orderBy('hora', 'desc')
        ->limit(50)
        ->get();
        $facturasExistentes = DB::table('facturas')->pluck('codigo')->toArray();

        return view('facturas.index', compact('facturascli', 'facturasExistentes'));
    }

    // En FacturasController

    public function detalleFactura($codigo = null)
    {
        $factura = null;
        $lineas = [];
        $cliente = null;
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
        $tipoDte = (int) $request->input('tipo_dte', 33);
        
        $productos = [];
        if (isset($data['productos'])) {
            foreach ($data['productos'] as $producto) {
                $precioConIva = isset($producto['precio']) ? round($producto['precio'] * 1.19) : 0;
                $productos[] = [
                    "cantidad" => $producto['cantidad'] ?? 0,
                    "nombre" => $producto['referencia'] ?? '',
                    "descripcion" => $producto['descripcion'] ?? '',
                    "precioUnitario" => $precioConIva,
                    "exento" => $producto['exento'] ?? false
                ];
            }
        }

        $referencias = [];
        if (isset($data['referencias']) && is_array($data['referencias']) && count($data['referencias']) > 0) {
            foreach ($data['referencias'] as $referencia) {
                if(isset($referencia['tipo_documento'])){
                    $referencias[] = [
                        "codigoTipoDteReferencia" => (int)$referencia['tipo_documento'],
                        "folioReferencia" => $referencia['folio_ref'],
                        "fechaDteReferenciado" => $referencia['fecha_ref'],
                        "razonReferencia" => $referencia['razon_referencia']
                    ];
                }
            }
        }

        $jsonRequest = [
            "credenciales" => [
                "rutEmisor" => "76269769-6",
                "rutContribuyente" => "17432554-5",
                "nombreSucursal" => "Casa Matriz"
            ],
            "dte" => [
                "codigoTipoDte" => $tipoDte,
                "indicadorMontosNetos" => false,
                "formaPago" => (int) $request->input('forma_pago', 2),
                "descuentoGlobal" => 0,
                "fechaEmision" => $data['fecha_emision'] ?? date('Y-m-d'),
                "diasVencimiento" => 30,
                "tieneIvaTerceros" => false,
                "ivaTerceros" => 0,
                "ivaPropio" => 0,
                "productos" => $productos
            ]
        ];

        if (count($referencias) > 0) {
            $jsonRequest["dte"]["tieneReferencias"] = true;
            $jsonRequest["dte"]["referencias"] = $referencias;
        }

        $jsonOutput = json_encode($jsonRequest, JSON_PRETTY_PRINT);

        // Simulando la respuesta
        $simulatedResponse = [
            "status" => 200,
            "message" => "Con fecha 13-07-2024 13:22:55, se emitió el DTE tipo FACTURA ELECTRONICA número 4903 desde la sucursal Casa Matriz del emisor ",
            "data" => [
                "folio" => 4903,
                "fechaEmision" => "09-01-2024"
            ],
            "errors" => null
        ];

        if ($simulatedResponse['status'] == 200 && is_null($simulatedResponse['errors'])) {
            DB::table('facturas')->insert([
                'codigo' => $request->input('codigo'),
                'tipo_dte' => $tipoDte,
                'folio' => $simulatedResponse['data']['folio'],
                'fechaEmision' => $simulatedResponse['data']['fechaEmision'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json($simulatedResponse, 200);

        //return response($jsonOutput, 200)->header('Content-Type', 'application/json');

        

        // Enviar el request a la API
        //$response = Http::post('https://api.example.com/factura', $jsonRequest);

        return response()->json([
            'status' => $response->successful(),
            'data' => $response->json()
        ]);
    }

    public function descargarFactura($codigo)
    {
        $factura = DB::table('facturas')->where('codigo', $codigo)->first();

        if (!$factura) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        }

        $jsonRequest = [
            "credenciales" => [
                "rutEmisor" => "76269769-6",
                "nombreSucursal" => "Casa Matriz"
            ],
            "dteReferenciadoExterno" => [
                "folio" => (int)$factura->folio,
                "codigoTipoDte" => (int)$factura->tipo_dte,
                "ambiente" => 0
            ]
        ];

        $jsonOutput = json_encode($jsonRequest, JSON_PRETTY_PRINT);
        //return response($jsonOutput, 200)->header('Content-Type', 'application/json');
        $username = config('services.simplefactura.username');
        $password = config('services.simplefactura.password');
        $authorization = base64_encode("{$username}:{$password}");
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic {$authorization}",
        ])->post('https://api.simplefactura.cl/dte/pdf', $jsonRequest);

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="factura.pdf"');
        }

        return response()->json(['error' => 'No se pudo descargar el PDF'], 500);
    
    }
}
