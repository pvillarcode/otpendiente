<?php

namespace App\Http\Controllers;

use App\Models\Albaranescli;
use App\Models\CheckboxState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class AlbaranescliController extends Controller
{
    /**
     * Muestra una lista de registros de albaranescli con estado 7.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $defaultRequest = new Request(['category' => 'templados']);
        $albaranescli = $this->getDataForTab($defaultRequest);

        return view('albaranescli.index', compact('albaranescli'));
    }

    public function laminado()
    {
        $defaultRequest = new Request(['category' => 'laminados']);
        $albaranescli = $this->getDataForTab($defaultRequest);

        // Aquí puedes implementar la lógica para obtener los datos específicos de "laminado"
        return view('albaranescli.laminado', compact('albaranescli')); // Ajusta el nombre de la vista según tu aplicación
    }

    /**
     * Obtiene datos según la categoría seleccionada.
     *
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDataForTab(Request $request)
    {
        $category = $request->input('category'); 
        switch ($category) {
            case 'templados':
                $codfamilias = ['CRISTEM', 'MAQ','MONOLITI'];               
                break;
            case 'laminados':
                $codfamilias = ['CRISCURV', 'CRISLAM'];
                break;
            case 'stock':
                $codfamilias = ['categoria_para_stock'];
                break;
            default:
                $codfamilias = [];
                break;
        }

        $albaranescli = Albaranescli::select('albaranescli.codigo', 'albaranescli.nombrecliente', 'albaranescli.observaciones', 'albaranescli.fecha as ingreso', 'albaranescli.numero2 as compromiso', DB::raw('MAX(albaranescli.fecha) as fecha_maxima'))
            ->join('lineasalbaranescli', 'albaranescli.idalbaran', '=', 'lineasalbaranescli.idalbaran')
            ->join('productos', 'lineasalbaranescli.idproducto', '=', 'productos.idproducto')
            ->whereIn('productos.codfamilia', $codfamilias)
            ->where('albaranescli.idestado', 7)
            ->groupBy('albaranescli.codigo', 'albaranescli.nombrecliente', 'albaranescli.observaciones','albaranescli.fecha', 'albaranescli.numero2')
            ->orderBy('fecha_maxima', 'desc')
            ->distinct()
            ->get();
        
        Carbon::setLocale('es');
        foreach ($albaranescli as $albaran) {
            $albaran->ingreso = Carbon::parse($albaran->ingreso)->isoFormat('MMM D');
        }
        foreach ($albaranescli as $albaran) {
            if (!empty($albaran->compromiso)) {
                try {
                    // Intenta crear la fecha con el formato esperado
                    $fechaCompromiso = Carbon::createFromFormat('d/m/Y', $albaran->compromiso);
                    $albaran->compromiso = $fechaCompromiso->isoFormat('MMM D');
                } catch (InvalidFormatException $e) {
                    // Si la fecha no está en el formato esperado, puedes decidir cómo manejarla
                    // Por ejemplo, podrías dejar el valor sin cambios o asignar una fecha por defecto
                    $albaran->compromiso = 'Fecha inválida';
                }
            }
        }

        $checkboxStates = CheckboxState::whereIn('codigo', $albaranescli->pluck('codigo'))->get();

        // Asignar los estados de checkboxes a cada albarán
        foreach ($albaranescli as $albaran) {
            $state = $checkboxStates->where('codigo', $albaran->codigo)->first();

            if ($state) {
                $albaran->matriz = $state->matriz;
                $albaran->corte = $state->corte;
                $albaran->pulido = $state->pulido;
                $albaran->perforado = $state->perforado;
                $albaran->pintado = $state->pintado;
                $albaran->curvado = $state->curvado;
                $albaran->empavonado = $state->empavonado;
                $albaran->laminado = $state->laminado;
                if(!empty($state->estado)){
                    $albaran->estado = $state->estado;
                } else {
                    $albaran->estado = '';
                }
                 

            } else {
                $albaran->matriz = false;
                $albaran->corte = false;
                $albaran->pulido = false;
                $albaran->perforado = false;
                $albaran->pintado = false;
                $albaran->curvado = false;
                $albaran->laminado = false;
                $albaran->empavonado = false;
                $albaran->estado = '';
            }
        }
        
        return $albaranescli;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category', 'templados');
    
        // Define los codfamilias según la categoría
        switch ($category) {
            case 'templados':                
                $codfamilias = ['CRISTEM', 'MAQ', 'MONOLITI'];
                break;
            case 'laminados':
                $codfamilias = ['CRISCURV', 'CRISLAM'];
                break;
            case 'stock':
                $codfamilias = ['categoria_para_stock'];
                break;
            default:
                $codfamilias = [];
                break;
        }
    
        // Realiza la consulta con los filtros necesarios
        $albaranescli = Albaranescli::select('albaranescli.codigo', 'albaranescli.nombrecliente', 'albaranescli.observaciones', 'albaranescli.fecha as ingreso', 'albaranescli.numero2 as compromiso', DB::raw('MAX(albaranescli.fecha) as fecha_maxima'))
            ->join('lineasalbaranescli', 'albaranescli.idalbaran', '=', 'lineasalbaranescli.idalbaran')
            ->join('productos', 'lineasalbaranescli.idproducto', '=', 'productos.idproducto')
            ->whereIn('productos.codfamilia', $codfamilias)
            ->where('albaranescli.idestado', 7)
            ->groupBy('albaranescli.codigo', 'albaranescli.nombrecliente', 'albaranescli.observaciones', 'albaranescli.fecha', 'albaranescli.numero2')
            ->orderBy('fecha_maxima', 'desc')
            ->distinct();
    
        if (!empty($query)) {
            $albaranescli = $albaranescli->where('albaranescli.codigo', 'LIKE', "%{$query}%");
        }
    
        $albaranescli = $albaranescli->get();

        Carbon::setLocale('es');
        foreach ($albaranescli as $albaran) {
            $albaran->ingreso = Carbon::parse($albaran->ingreso)->isoFormat('MMM D');
        }
        foreach ($albaranescli as $albaran) {
            if (!empty($albaran->compromiso)) {
                try {
                    // Intenta crear la fecha con el formato esperado
                    $fechaCompromiso = Carbon::createFromFormat('d/m/Y', $albaran->compromiso);
                    $albaran->compromiso = $fechaCompromiso->isoFormat('MMM D');
                } catch (InvalidFormatException $e) {
                    // Si la fecha no está en el formato esperado, puedes decidir cómo manejarla
                    // Por ejemplo, podrías dejar el valor sin cambios o asignar una fecha por defecto
                    $albaran->compromiso = 'Fecha inválida';
                }
            }
        }
        
        // Obtén los estados de los checkboxes
        $checkboxStates = CheckboxState::whereIn('codigo', $albaranescli->pluck('codigo'))->get();
        foreach ($albaranescli as $albaran) {
            $state = $checkboxStates->where('codigo', $albaran->codigo)->first();
            if ($state) {
                $albaran->matriz = $state->matriz;
                $albaran->corte = $state->corte;
                $albaran->pulido = $state->pulido;
                $albaran->perforado = $state->perforado;
                $albaran->empavonado = $state->empavonado;
                $albaran->pintado = $state->pintado;
                $albaran->curvado = $state->curvado;
                $albaran->laminado = $state->laminado;
                $albaran->estado = $state->estado ?? '';
            } else {
                $albaran->matriz = false;
                $albaran->corte = false;
                $albaran->pulido = false;
                $albaran->perforado = false;
                $albaran->pintado = false;
                $albaran->curvado = false;
                $albaran->empavonado = false;
                $albaran->laminado = false;
                $albaran->estado = '';
            }
        }
        return response()->json($albaranescli);
    }
    
    // Otros métodos como search y updateCheckboxState se mantienen igual
}
