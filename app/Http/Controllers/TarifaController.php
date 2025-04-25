<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class TarifaController extends Controller
{
    // Mostrar todas las tarifas
    public function index()
    {
        $tarifas = Tarifa::all();
        return view('tarifas.index', compact('tarifas'));
    }

    // Mostrar el formulario para crear una nueva tarifa
    public function create()
    {
        return view('tarifas.create');
    }

    // Guardar una nueva tarifa
    public function store(Request $request)
    {
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
        $tarifa = Tarifa::findOrFail($id);
        return view('tarifas.edit', compact('tarifa'));
    }

    // Actualizar una tarifa existente
    public function update(Request $request, $id)
    {
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

    // Eliminar una tarifa
    public function destroy($id)
    {
        $tarifa = Tarifa::findOrFail($id);

        // Verificar si la tarifa está asignada a algún cliente
        if ($tarifa->clientes()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede eliminar una tarifa asignada a un cliente.',
            ], 400);
        }

        $tarifa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tarifa eliminada con éxito.',
        ]);
    }

    public function descargaPDF()
    {
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

