<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class TarifaController extends Controller
{
    // Mostrar todas las tarifas
    public function index()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $tarifas = Tarifa::all();
        return Inertia::render('tarifas/tarifasIndex', [
            'tarifas' => $tarifas,
        ]);
    }

    // Mostrar el formulario para crear una nueva tarifa
    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        return view('tarifas.create');
    }

    // Guardar una nueva tarifa
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descuento' => 'required|numeric|min:0|max:100',
        ]);

        Tarifa::create([
            'nombre' => $request->nombre,
            'descuento' => $request->descuento,
        ]);

        return redirect()->route('tarifas.index')->with('success', 'Tarifa creada con éxito');
    }

    // Mostrar el formulario para editar una tarifa existente
    public function edit($id)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $tarifa = Tarifa::findOrFail($id);
        return view('tarifas.edit', compact('tarifa'));
    }

    // Actualizar una tarifa existente
    public function update(Request $request, $id)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descuento' => 'required|numeric|min:0|max:100',
        ]);

        $tarifa = Tarifa::findOrFail($id);
        $tarifa->update([
            'nombre' => $request->nombre,
            'descuento' => $request->descuento,
        ]);

        return redirect()->route('tarifas.index')->with('success', 'Tarifa actualizada con éxito');
    }

    public function destroy($id)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        try {
            $tarifa = Tarifa::findOrFail($id);

            // Verificar si la tarifa está asignada a algún cliente
            if ($tarifa->clientes()->exists()) {
                return Inertia::render('tarifas/tarifasIndex', [
                    'tarifas' => Tarifa::all(),
                    'error' => 'No se puede eliminar una tarifa asignada a un cliente.',
                ]);
            }

            $tarifa->delete();

            return Inertia::render('tarifas/tarifasIndex', [
                'tarifas' => Tarifa::all(),
                'success' => 'Tarifa eliminada con éxito.',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('tarifas/tarifasIndex', [
                'tarifas' => Tarifa::all(),
                'error' => 'Error al eliminar la tarifa.',
            ]);
        }
    }

    public function descargaPDF()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $tarifas = Tarifa::all();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarTarifas', compact('tarifas'))->render();
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // (Opcional) Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('tarifas.pdf');
    }

}

