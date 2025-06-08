<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

// Ruta de inicio
Route::get('/', function () {
    return Inertia::render('Inicio');
})->middleware(['auth', 'verified']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Dashboard solo para el admin
Route::get('/dashboard', function () {
    if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
    }
    return Inertia::render('Inicio');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Carrito y perfil usuario
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito', [CarritoController::class, 'store']);
    Route::delete('/carrito/{id}', [CarritoController::class, 'destroy']);
    Route::delete('/carrito', [CarritoController::class, 'clear']);

    Route::post('/carrito/guardar', [AlquilerController::class, 'guardarDesdeCarrito'])->name('carrito.guardar');
    Route::get('/mis-alquileres', [ClienteController::class, 'misAlquileres'])->name('cliente.misAlquileres');

    Route::get('/perfil', [ClienteController::class, 'verVistaPerfil']);

    Route::get('/perfil/datos', [ClienteController::class, 'perfil']);

    Route::post('/perfil', [ClienteController::class, 'actualizarPerfil']);
    
    Route::get('/peliculas-usuario', [ClienteController::class, 'peliculas'])->name('peliculas.usuario');

    Route::get('/mi-tarifa', [ClienteController::class, 'verTarifa'])->name('cliente.verTarifa');
    Route::post('/mi-tarifa', [ClienteController::class, 'cambiarTarifa'])->name('cliente.cambiarTarifa');

    // Rutas de administración solo para el admin
    Route::group([
        'middleware' => function ($request, $next) {
            if (Auth::user()->rol !== 'administrador') {
                abort(403, 'No tienes permiso para acceder aquí.');
            }
            return $next($request);
        }
    ], function () {
        // Clientes
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/descargar-pdf', [ClienteController::class, 'descargaPDF'])->name('descargarPDF.descargarClientes');
        Route::get('/clientes/descargar-csv', [ClienteController::class, 'descargarCSV'])->name('descargarCSV.clientes');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{dni}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{dni}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

        // Tarifas
        Route::get('/tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
        Route::post('/tarifas', [TarifaController::class, 'store'])->name('tarifas.store');
        Route::put('/tarifas/{id}', [TarifaController::class, 'update'])->name('tarifas.update');
        Route::delete('/tarifas/{id}', [TarifaController::class, 'destroy'])->name('tarifas.destroy');
        Route::get('/tarifas/descargar-pdf', [TarifaController::class, 'descargaPDF'])->name('descargarPDF.descargarTarifas');
        Route::get('/tarifas/descargar-csv', [TarifaController::class, 'descargarCSV'])->name('descargarCSV.tarifas');

        // Películas
        Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
        Route::post('/peliculas', [PeliculaController::class, 'store'])->name('peliculas.store');
        Route::put('/peliculas/{pelicula}', [PeliculaController::class, 'update'])->name('peliculas.update');
        Route::delete('/peliculas/{pelicula}', [PeliculaController::class, 'destroy'])->name('peliculas.destroy');
        Route::get('/peliculas/descargar-pdf', [PeliculaController::class, 'descargaPDF'])->name('descargarPDF.descargarPeliculas');
        Route::get('/peliculas/descargar-csv', [PeliculaController::class, 'descargarCSV'])->name('descargarCSV');

        // Alquileres
        Route::get('/alquileres', [AlquilerController::class, 'index'])->name('alquileres.index');
        Route::post('/alquileres', [AlquilerController::class, 'store'])->name('alquileres.store');
        Route::put('/alquileres/{alquiler}', [AlquilerController::class, 'update'])->name('alquileres.update');
        Route::delete('/alquileres/{alquiler}', [AlquilerController::class, 'destroy'])->name('alquileres.destroy');
        Route::get('/alquileres/descargar-pdf', [AlquilerController::class, 'descargaPDF'])->name('descargarPDF.descargarAlquileres');
        Route::get('/alquileres/descargar-csv', [AlquilerController::class, 'descargarCSV'])->name('descargarCSV.alquileres');

        // Categorías
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
        Route::get('/categorias/descargar-pdf', [CategoriaController::class, 'descargaPDF'])->name('descargarPDF.descargarCategorias');
        Route::get('/categorias/descargar-csv', [CategoriaController::class, 'descargarCSV'])->name('descargarCSV.categorias');
    });

    // Profile (de Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';