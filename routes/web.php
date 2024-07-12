<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AlbaranescliController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\CheckboxStateController;
use App\Events\CheckboxUpdated;

// Ruta para cargar la vista HTML de Albaranescli a través del controlador
//Route::get('/albaranescli', [AlbaranescliController::class, 'index'])->name('albaranescli.index');
Route::get('/albaranescli', [AlbaranescliController::class, 'index'])->name('albaranescli.index')->middleware('auth');
Route::get('/laminado', [AlbaranescliController::class, 'laminado'])->name('albaranescli.laminado')->middleware('auth');


// Ruta para manejar las actualizaciones de los checkboxes
Route::post('/update-checkbox', [CheckboxStateController::class, 'update'])->name('checkbox.update');

Route::get('/search-albaranescli', [AlbaranescliController::class, 'search'])->name('albaranescli.search');

Route::post('/update-estado', [CheckboxStateController::class, 'updateEstado'])->name('estado.update');
Route::get('/get-data-for-tab', [AlbaranescliController::class, 'getDataForTab'])->name('get-data-for-tab');



// Ruta adicional para cargar la vista HTML sin pasar por el controlador (puedes cambiar la URL si deseas)
Route::get('/albaranescli/view', function () {
    return view('albaranescli.index');
})->name('albaranescli.view'); // Nombre opcional para la ruta  
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', '/albaranescli');
/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => false,
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/facturas', [FacturasController::class, 'index'])->name('facturas.index');
Route::get('/facturas/detalle/{codigo?}', [FacturasController::class, 'detalleFactura'])->name('facturas.detalle');
Route::post('/enviar-factura', [FacturasController::class, 'enviarFactura'])->name('enviar.factura');

/*
Route::get('/testevent',function(){
    return view('fireevent');
});


Route::post('/testevent',function(){
    $name = request()->name;
    event(new CheckboxUpdated($name));
});

Route::get('/welcome',function (){
    return view('welcome');
});*/


require __DIR__.'/auth.php';
