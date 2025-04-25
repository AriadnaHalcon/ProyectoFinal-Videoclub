<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente; // Modelo de Cliente
use App\Models\Tarifa;
use Dompdf\Dompdf;
use Dompdf\Options;

class ClienteController extends Controller
{
    // Mostrar el listado de clientes
    public function index()
    {
        $clientes = Cliente::all();
        $tarifas = Tarifa::all();  // Obtener todas las tarifas
        return view('clientes.index', compact('clientes', 'tarifas'));
    }

    // Mostrar formulario para crear un cliente
    public function create()
    {
        $tarifas = Tarifa::all(); 
        return view('clientes.create', compact('tarifas'));
    }

    // Guardar un nuevo cliente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|unique:clientes',
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:clientes,email',
            'id_tarifa' => 'nullable|exists:tarifas,id_tarifa',
        ]);

        $tarifaStandard = Tarifa::where('nombre', 'Estándar')->first();

        $validated['id_tarifa'] = $validated['id_tarifa'] ?? $tarifaStandard->id_tarifa;

        try {
            // Crear el cliente en la base de datos
            $cliente = Cliente::create($validated);
    
            // Redirigir a la lista de clientes con un mensaje de éxito
            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
        } catch (\Exception $e) {
            // Redirigir a la lista de clientes con un mensaje de error
            return redirect()->route('clientes.index')->with('error', 'Error al crear el cliente.');
        }
    }

    // Mostrar formulario para editar un cliente
    public function edit($dni)
    {
        $cliente = Cliente::findOrFail($dni);
        return response()->json($cliente);
    }

    // Actualizar los datos de un cliente
    public function update(Request $request, $dni)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:clientes,email,' . $dni . ',dni',
            'id_tarifa' => 'nullable|exists:tarifas,id_tarifa',
        ]);

        try {
            $cliente = Cliente::findOrFail($dni); // Buscar cliente por su DNI

            // Verificar si el cliente tiene una tarifa asignada, si no, asignar la tarifa estándar
            if (!$cliente->tarifa) {
                $tarifaStandard = Tarifa::where('nombre', 'Estándar')->firstOrFail();

                // Asignar la tarifa estándar si no hay tarifa asignada
                $cliente->id_tarifa = $tarifaStandard->id_tarifa;
            }

            $cliente->fill($request->all()); // Llenar cliente con los datos del request
            $cliente->save(); // Guardar cliente

            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Error al actualizar el cliente.');
        }
    }

    // Eliminar un cliente
    public function destroy($dni)
    {
        try {
            $cliente = Cliente::findOrFail($dni); // Buscar cliente por su DNI
            $cliente->delete(); // Eliminar cliente
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Error al eliminar el cliente.');
        }
    }

    public function descargaPDF()
    {
        $clientes = Cliente::all();
        $tarifas = Tarifa::all();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarClientes', compact('clientes', 'tarifas'))->render();
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // (Opcional) Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('clientes.pdf');
    }
}