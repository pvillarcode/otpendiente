<?php
// routes/web.php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbaranescliController;
use App\Http\Controllers\CheckboxStateController;

// Ruta para cargar la vista HTML de Albaranescli a través del controlador
Route::get('/albaranescli', [AlbaranescliController::class, 'index'])->name('albaranescli.index');

// Ruta para manejar las actualizaciones de los checkboxes
Route::post('/update-checkbox', [CheckboxStateController::class, 'update'])->name('checkbox.update');

Route::get('/search-albaranescli', [AlbaranescliController::class, 'search'])->name('albaranescli.search');

Route::post('/update-estado', [CheckboxStateController::class, 'updateEstado'])->name('estado.update');



// Ruta adicional para cargar la vista HTML sin pasar por el controlador (puedes cambiar la URL si deseas)
Route::get('/albaranescli/view', function () {
    return view('albaranescli.index');
})->name('albaranescli.view'); // Nombre opcional para la ruta
