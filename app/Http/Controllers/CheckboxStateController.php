<?php

// app/Http/Controllers/CheckboxStateController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CheckboxState;
use App\Events\CheckboxUpdated;

class CheckboxStateController extends Controller
{
    /**
     * Actualiza el estado de un checkbox en la base de datos SQLite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        DB::enableQueryLog();

        $codigo = $request->codigo;
        $columna = $request->columna;
        $valor = $request->valor;
    
        Log::info("Updating checkbox state: codigo={$codigo}, columna={$columna}, valor={$valor}");
    
        try {
            DB::beginTransaction();
    
            $checkboxState = CheckboxState::where('codigo', $codigo)->first();
    
            if ($checkboxState) {
                Log::info("Updating existing record for codigo: {$codigo}");
                $checkboxState->$columna = $valor;
                $checkboxState->codigo = $codigo;  // Explicitly set the codigo
                $checkboxState->save();
            } else {
                Log::info("Creating new record for codigo: {$codigo}");
                $checkboxState = new CheckboxState();
                $checkboxState->codigo = $codigo;
                $checkboxState->$columna = $valor;
                $checkboxState->save();
            }
    
            DB::commit();
            event(new CheckboxUpdated($checkboxState));

            Log::info("Executed queries: " . json_encode(DB::getQueryLog()));
            Log::info("Final checkbox state: " . $checkboxState->toJson());
    
            return response()->json(['success' => true, 'data' => $checkboxState]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating checkbox state: " . $e->getMessage());
            Log::error("Executed queries: " . json_encode(DB::getQueryLog()));
    
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }



    public function updateEstado(Request $request)
    {
        $codigo = $request->codigo;
        $estado = $request->estado;

        try {

            DB::beginTransaction();
            $checkboxState = CheckboxState::where('codigo', $codigo)->first();

            if ($checkboxState) {
                Log::info("Updating existing record for codigo: {$codigo}");
                $checkboxState->estado = $estado;
                $checkboxState->codigo = $codigo; 
                $checkboxState->save();
            } else {
                Log::info("Creating new record for codigo: {$codigo}");
                $checkboxState = new CheckboxState();
                $checkboxState->codigo = $codigo;  
                $checkboxState->estado = $estado;
                $checkboxState->save();
            }

            DB::commit();
            event(new CheckboxUpdated($checkboxState));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // app/Http/Controllers/CheckboxStateController.php

// app/Http/Controllers/CheckboxStateController.php

    public function updateAllCheckboxes(Request $request)
    {
        Log::info('Received request', ['method' => $request->method(), 'data' => $request->all()]);

        $codigo = $request->codigo;
        $updates = $request->updates;

        try {
            DB::beginTransaction();

            $checkboxState = CheckboxState::where('codigo', $codigo)->first();

            if ($checkboxState) {
                foreach ($updates as $update) {
                    $columna = $update['columna'];
                    $valor = $update['valor'];
                    $checkboxState->setAttribute($columna, $valor);
                }
                $checkboxState->save();
            } else {
                $checkboxState = new CheckboxState();
                $checkboxState->codigo = $codigo;
                foreach ($updates as $update) {
                    $columna = $update['columna'];
                    $valor = $update['valor'];
                    $checkboxState->setAttribute($columna, $valor);
                }
                $checkboxState->save();
            }

            DB::commit();
            event(new CheckboxUpdated($checkboxState));
            Log::info("Evento CheckboxUpdated disparado", ['checkboxState' => $checkboxState]);

            return response()->json(['success' => true, 'data' => $checkboxState]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating checkbox state: " . $e->getMessage());
            Log::error("Executed queries: " . json_encode(DB::getQueryLog()));

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function updateDisabled(Request $request)
    {
        $codigo = $request->input('codigo');
        $column = $request->input('column');
        $disabled = $request->input('disabled');

        $checkboxState = CheckboxState::where('codigo', $codigo)->first();
        if ($checkboxState) {
            $checkboxState->$column = $disabled;
            $checkboxState->save();

            event(new CheckboxStateUpdated($checkboxState));
        }

        return response()->json(['success' => true]);
    }

}
