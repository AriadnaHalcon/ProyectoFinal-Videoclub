<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class AlquilerController extends Controller
{
    public function index()
    {
        $alquileres = Alquiler::with(['cliente', 'pelicula'])->get();
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();
        
        $alquileres->each(function ($alquiler) {
            $alquiler->fecha_alquiler = date('d/m/Y', strtotime($alquiler->fecha_alquiler));
            $alquiler->fecha_devolucion = $alquiler->fecha_devolucion ? date('d/m/Y', strtotime($alquiler->fecha_devolucion)) : null;
        });

        return view('alquileres.index', compact('alquileres', 'clientes', 'peliculas'));  // Pasar las tres variables
    }

    public function create()
    {
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();
        return view('alquileres.create', compact('clientes', 'peliculas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni_cliente' => 'required|exists:clientes,dni',
            'id_pelicula' => 'required|exists:peliculas,id_pelicula',
            'fecha_alquiler' => 'required|date',
            'fecha_devolucion' => 'nullable|date',
            'estado' => 'required|in:Pendiente,Devuelto',
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

        \Log::info("Precio original: {$pelicula->precio}, Descuento: " . (isset($descuento) ? $descuento : 0) . ", Precio rebajado: {$precioRebajado}");
        return redirect()->route('alquileres.index')->with('success', 'Alquiler registrado correctamente');
    }

    public function edit(Alquiler $alquiler)
    {
        $clientes = Cliente::all();
        $peliculas = Pelicula::all();
        return view('alquileres.edit', compact('alquiler', 'clientes', 'peliculas'));
    }

    public function update(Request $request, Alquiler $alquiler)
    {
        $request->validate([
            'dni_cliente' => 'required',
            'id_pelicula' => 'required',
            'fecha_devolucion' => 'nullable|date',
            'estado' => 'required|in:Pendiente,Devuelto',
        ]);

        // Obtener la película asociada al alquiler
        $pelicula = Pelicula::findOrFail($alquiler->id_pelicula);

        // Verificar el estado anterior del alquiler
        $estadoAnterior = $alquiler->estado;

        // Actualizar el alquiler con los nuevos datos
        $alquiler->update($request->all());

        // Verificar el nuevo estado del alquiler
        $nuevoEstado = $request->estado;

        // Ajustar el stock de la película según el estado del alquiler
        if ($estadoAnterior == 'Pendiente' && $nuevoEstado == 'Devuelto') {
            // Incrementar el stock si el estado cambia de pendiente a devuelto
            $pelicula->stock += 1;
        } elseif ($estadoAnterior == 'Devuelto' && $nuevoEstado == 'Pendiente') {
            // Disminuir el stock si el estado cambia de devuelto a pendiente
            $pelicula->stock -= 1;
        }

        // Guardar los cambios en la película
        $pelicula->save();

        return redirect()->route('alquileres.index')->with('success', 'Alquiler actualizado correctamente.');
    }

    public function destroy(Alquiler $alquiler)
    {
        $alquiler->delete();
        return redirect()->route('alquileres.index')->with('success', 'Alquiler eliminado correctamente.');
    }

    public function descargaPDF()
    {
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

        // (Opcional) Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('alquileres.pdf');
    }
}
