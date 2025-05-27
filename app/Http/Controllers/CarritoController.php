<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    public function index()
    {
        $dni = Auth::user()->dni;

        $carrito = Carrito::with('pelicula')
            ->where('dni_cliente', $dni)
            ->get();

        return response()->json($carrito);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelicula' => 'required|exists:peliculas,id_pelicula',
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = Carrito::updateOrCreate(
            [
                'dni_cliente' => Auth::user()->dni,
                'id_pelicula' => $request->id_pelicula,
            ],
            ['cantidad' => DB::raw("cantidad + {$request->cantidad}")]
        );

        return response()->json($carrito);
    }

    public function destroy($id)
    {
        $carrito = Carrito::findOrFail($id);

        if ($carrito->dni_cliente !== Auth::user()->dni) {
            abort(403, 'No autorizado');
        }

        $carrito->delete();

        return response()->json(['message' => 'Película eliminada del carrito']);
    }

    public function clear()
    {
        Carrito::where('dni_cliente', Auth::user()->dni)->delete();

        return response()->json(['message' => 'Carrito vacío']);
    }

    public function guardar(Request $request)
    {
        $user = Auth::user();
        $datos = $request->validate([
            'items' => 'required|array',
            'items.*.id_pelicula' => 'required|integer|exists:peliculas,id_pelicula',
            'items.*.tipo' => 'required|string|in:alquilar,comprar',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        foreach ($datos['items'] as $item) {
            // Guardar cada elemento en la tabla carrito con dni_cliente = usuario logueado
            \App\Models\Carrito::create([
                'dni_cliente' => $user->dni, // o el campo que use tu usuario para identificarlo
                'id_pelicula' => $item['id_pelicula'],
                'cantidad' => $item['cantidad'],
                // si quieres guardar tipo (alquilar/comprar), deberías agregar ese campo en la tabla carrito
                // 'tipo' => $item['tipo'],
            ]);
        }

        return response()->json(['mensaje' => 'Compra guardada correctamente']);
    }

}

