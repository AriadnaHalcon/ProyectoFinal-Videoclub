<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AlquilerController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permiso para acceder aquí.');
        }

        $alquileres = Alquiler::with(['cliente.tarifa', 'pelicula'])->get()->map(function ($alquiler) {
            $descuento = $alquiler->cliente->tarifa->descuento ?? 0;
            $precioOriginal = $alquiler->pelicula->precio ?? 0;
            $alquiler->precio_rebajado = $precioOriginal - ($precioOriginal * $descuento / 100);

            $alquiler->fecha_alquiler = date('d/m/Y', strtotime($alquiler->fecha_alquiler));
            $alquiler->fecha_devolucion = $alquiler->fecha_devolucion ? date('d/m/Y', strtotime($alquiler->fecha_devolucion)) : null;

            return $alquiler;
        });

        $clientes = Cliente::with('tarifa')->get();
        $peliculas = Pelicula::all();

        return \Inertia\Inertia::render('alquileres/alquileresIndex', [
            'alquileres' => $alquileres,
            'clientes' => $clientes,
            'peliculas' => $peliculas,
        ]);
    }

    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();
        return view('alquileres.create', compact('clientes', 'peliculas'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $validated = $request->validate([
            'dni_cliente' => 'required|exists:clientes,dni',
            'id_pelicula' => 'required|exists:peliculas,id_pelicula',
            'fecha_alquiler' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_alquiler',
            'estado' => 'required|in:Pendiente,Devuelto',
        ], [
            'fecha_alquiler.required' => 'La fecha de alquiler es obligatoria.',
            'fecha_alquiler.date' => 'La fecha de alquiler no es válida.',
            'fecha_devolucion.date' => 'La fecha de devolución no es válida.',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de alquiler.',
        ]);

        $cliente = Cliente::find($validated['dni_cliente']);
        $pelicula = Pelicula::find($validated['id_pelicula']);
        
        if ($pelicula->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede alquilar la película porque no hay stock disponible.'
            ], 400);
        }

        $precioRebajado = $pelicula->precio;

        if ($cliente->tarifa) {
            $descuento = $cliente->tarifa->descuento;
            $precioRebajado = $pelicula->precio * (1 - ($descuento / 100));
        }

        $validated['precio_rebajado'] = $precioRebajado;
        $validated['fecha_alquiler'] = now(); 
        $validated['estado'] = 'Pendiente'; 
        
        Alquiler::create($validated);

        Log::info("Precio original: {$pelicula->precio}, Descuento: " . (isset($descuento) ? $descuento : 0) . ", Precio rebajado: {$precioRebajado}");
        return redirect()->route('alquileres.index')->with('success', 'Alquiler registrado correctamente');
    }

    public function edit(Alquiler $alquiler)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();
        return view('alquileres.edit', compact('alquiler', 'clientes', 'peliculas'));
    }

    public function update(Request $request, Alquiler $alquiler)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'dni_cliente' => 'required',
            'id_pelicula' => 'required',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_alquiler',
            'estado' => 'required|in:Pendiente,Devuelto',
        ], [
            'fecha_devolucion.date' => 'La fecha de devolución no es válida.',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de alquiler.',
        ]);

        // Obtiene la película asociada al alquiler
        $pelicula = Pelicula::findOrFail($alquiler->id_pelicula);

        // Verifica el estado anterior del alquiler
        $estadoAnterior = $alquiler->estado;

        // Actualiza el alquiler
        $alquiler->update($request->all());

        // Verifica el nuevo estado del alquiler
        $nuevoEstado = $request->estado;

        if ($estadoAnterior == 'Pendiente' && $nuevoEstado == 'Devuelto') {
            // Incrementa el stock
            $pelicula->stock += 1;
        } elseif ($estadoAnterior == 'Devuelto' && $nuevoEstado == 'Pendiente') {
            // Disminuye el stock
            $pelicula->stock -= 1;
        }

        $pelicula->save();

        return redirect()->route('alquileres.index')->with('success', 'Alquiler actualizado correctamente.');
    }

    public function destroy(Alquiler $alquiler)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $alquiler->delete();
        return redirect()->route('alquileres.index')->with('success', 'Alquiler eliminado correctamente.');
    }

    public function descargaPDF()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $alquileres = Alquiler::with(['cliente', 'pelicula'])->get();
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarAlquileres', compact('alquileres', 'clientes', 'peliculas'))->render();
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // Configura el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('alquileres.pdf');
    }

    public function descargarCSV()
    {
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permiso para acceder aquí.');
        }
        $alquileres = Alquiler::with(['cliente', 'pelicula'])->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="alquileres.csv"',
        ];
        $callback = function() use ($alquileres) {
            echo chr(239) . chr(187) . chr(191);
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Id', 'Cliente', 'Película', 'Fecha Alquiler', 'Fecha Devolución', 'Estado', 'Precio Rebajado'], ';');
            foreach ($alquileres as $a) {
                fputcsv($handle, [
                    $a->id_alquiler,
                    $a->cliente ? $a->cliente->nombre : '',
                    $a->pelicula ? $a->pelicula->titulo : '',
                    $a->fecha_alquiler,
                    $a->fecha_devolucion,
                    $a->estado,
                    $a->precio_rebajado,
                ], ';');
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'alquileres.csv', $headers);
    }

    public function guardarDesdeCarrito(Request $request)
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->firstOrFail();
        $tarifa = $cliente->tarifa;
        $descuento = $tarifa ? $tarifa->descuento : 0;

        foreach ($request->items as $item) {
            $pelicula = \App\Models\Pelicula::findOrFail($item['id_pelicula']);

            if ($pelicula->stock < $item['cantidad']) {
                return response()->json(['error' => 'No hay suficiente stock para ' . $pelicula->titulo], 400);
            }

            if ($item['tipo'] === 'alquilar') {
                $precioOriginal = $pelicula->precio;
                $precioRebajado = $precioOriginal * (1 - $descuento / 100);

                for ($i = 0; $i < $item['cantidad']; $i++) {
                    \App\Models\Alquiler::create([
                        'dni_cliente' => $cliente->dni,
                        'id_pelicula' => $pelicula->id_pelicula,
                        'fecha_alquiler' => now(),
                        'fecha_devolucion' => now()->addWeek(),
                        'estado' => 'Pendiente',
                        'precio_rebajado' => $precioRebajado,
                    ]);
                }
            } else if ($item['tipo'] === 'comprar') {
                // Descuenta stock si la opcion es "Comprar.
                $pelicula->stock -= $item['cantidad'];
                $pelicula->save();
            }
        }

        return response()->json(['success' => 'Operación realizada correctamente']);
    }

}
