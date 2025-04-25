<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta para la página de inicio (welcome)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Rutas protegidas por el middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::put('/clientes/{dni}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::get('/descargarPDF/descargarClientes', [ClienteController::class, 'descargaPDF'])->name('descargarPDF.descargarClientes');


    // Alquileres
    Route::resource('alquileres', AlquilerController::class)->parameters([
        'alquileres' => 'alquiler',
    ]);
    Route::get('/descargarPDF/descargarAlquileres', [AlquilerController::class, 'descargaPDF'])->name('descargarPDF.descargarAlquileres');


    // Películas
    Route::resource('peliculas', PeliculaController::class);
    Route::put('peliculas/{pelicula}', [PeliculaController::class, 'update'])->name('peliculas.update');
    Route::get('/descargarPDF/descargarPeliculas', [PeliculaController::class, 'descargaPDF'])->name('descargarPDF.descargarPeliculas');

    // Categorías
    Route::resource('categorias', CategoriaController::class);
    Route::get('/descargarPDF/descargarCategorias', [CategoriaController::class, 'descargaPDF'])->name('descargarPDF.descargarCategorias');


    // Tarifas
    Route::resource('tarifas', TarifaController::class);
    Route::get('/descargarPDF/descargarTarifas', [TarifaController::class, 'descargaPDF'])->name('descargarPDF.descargarTarifas');

});

