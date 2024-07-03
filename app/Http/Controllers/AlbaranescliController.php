<?php

// app/Http/Controllers/AlbaranescliController.php
// app/Http/Controllers/AlbaranescliController.php

namespace App\Http\Controllers;

use App\Models\Albaranescli;
use App\Models\CheckboxState;
use Illuminate\Http\Request;

class AlbaranescliController extends Controller
{
    /**
     * Muestra una lista de registros de albaranescli con estado 7.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albaranescli = Albaranescli::where('idestado', 7)
                                    ->select('codigo', 'nombrecliente', 'observaciones')
                                    ->get();

        $checkboxStates = CheckboxState::whereIn('codigo', $albaranescli->pluck('codigo'))->get();

        // Asignar los estados de checkboxes a cada albarán
        foreach ($albaranescli as $albaran) {
            $state = $checkboxStates->where('codigo', $albaran->codigo)->first();
            if ($state) {
                $albaran->corte = $state->corte;
                $albaran->pulido = $state->pulido;
                $albaran->perforado = $state->perforado;
                $albaran->pintado = $state->pintado;
                $albaran->estado = $state->estado;

            } else {
                $albaran->corte = false;
                $albaran->pulido = false;
                $albaran->perforado = false;
                $albaran->pintado = false;
                $albaran->perforado = '';
            }
        }
        return view('albaranescli.index', compact('albaranescli'));
    }

    public function search(Request $request)
    {
        $albaranescli = Albaranescli::where('idestado', 7);
        

        if (!empty($query)) {
            $albaranescli = $albaranescli->where('codigo', 'LIKE', "%{$query}%");
        }
    
        $albaranescli = $albaranescli->select('codigo', 'nombrecliente', 'observaciones')
                                     ->get();

                                     
        $checkboxStates = CheckboxState::whereIn('codigo', $albaranescli->pluck('codigo'))->get();
        foreach ($albaranescli as $albaran) {
            $state = $checkboxStates->where('codigo', $albaran->codigo)->first();
            if ($state) {
                $albaran->corte = $state->corte;
                $albaran->pulido = $state->pulido;
                $albaran->perforado = $state->perforado;
            } else {
                $albaran->corte = false;
                $albaran->pulido = false;
                $albaran->perforado = false;
                $albaran->pintado = $state->pintado;
                $albaran->pintado = false;
            }
        }
        return response()->json($albaranescli);
    }
}


