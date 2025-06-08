<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente; 
use App\Models\Tarifa;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;




class ClienteController extends Controller
{
    // Mostrar listado de clientes
    public function index()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $clientes = Cliente::with('tarifa')->get();
        $tarifas = Tarifa::all();

        return Inertia::render('clientes/clientesIndex', [
            'clientes' => $clientes,
            'tarifas' => $tarifas,
        ]);
    }

    // Mostrar formulario para crear cliente
    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $tarifas = Tarifa::all(); 
        return view('clientes.create', compact('tarifas'));
    }

    // Guardar nuevo cliente
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
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
            // Crear cliente en la base de datos
            $cliente = Cliente::create($validated);
    
            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Error al crear el cliente.');
        }
    }

    // Mostrar formulario para editar cliente
    public function edit($dni)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $cliente = Cliente::findOrFail($dni);
        return response()->json($cliente);
    }

    // Actualizar los datos del cliente
    public function update(Request $request, $dni)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:clientes,email,' . $dni . ',dni',
            'id_tarifa' => 'nullable|exists:tarifas,id_tarifa',
        ]);

        try {
            $cliente = Cliente::findOrFail($dni); // Busca cliente por su DNI

            if (!$cliente->tarifa) {
                $tarifaStandard = Tarifa::where('nombre', 'Estándar')->firstOrFail();

                // Asigna la tarifa estándar si no hay tarifa asignada
                $cliente->id_tarifa = $tarifaStandard->id_tarifa;
            }

            $cliente->fill($request->all()); 
            $cliente->save();


            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Error al actualizar el cliente.');
        }
    }

    // Eliminar un cliente
    public function destroy($dni)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        try {
            $cliente = Cliente::findOrFail($dni);
            if ($cliente->user_id) {
                $user = \App\Models\User::find($cliente->user_id);
                if ($user && $user->rol === 'usuario') {
                    $user->delete();
                }
            }
            $cliente->delete(); 
            return redirect()->route('clientes.index')->with('success', 'Cliente y usuario eliminados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Error al eliminar el cliente.');
        }
    }

    public function descargaPDF()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
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

        // Configurar el tamaño del papel y la orentación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('clientes.pdf');
    }

    public function descargarCSV()
    {
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permiso para acceder aquí.');
        }
        $clientes = Cliente::with('tarifa')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="clientes.csv"',
        ];
        $callback = function() use ($clientes) {
            // Escribir BOM para UTF-8
            echo chr(239) . chr(187) . chr(191);
            $handle = fopen('php://output', 'w');
            // Cabezera
            fputcsv($handle, ['DNI', 'Nombre', 'Dirección', 'Teléfono', 'Email', 'Tarifa'], ';');
            // Datos
            foreach ($clientes as $c) {
                fputcsv($handle, [
                    $c->dni,
                    $c->nombre,
                    $c->direccion,
                    $c->telefono,
                    $c->email,
                    $c->tarifa ? $c->tarifa->nombre : '',
                ], ';'); // <-- Punto y coma para separar los campos
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'clientes.csv', $headers);
    }

    public function perfil()
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        return response()->json([
            'dni' => $cliente->dni,
            'nombre' => $cliente->nombre,
            'direccion' => $cliente->direccion,
            'telefono' => $cliente->telefono,
            'email' => $cliente->email,
        ]);
    }

    public function verVistaPerfil()
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        $tarifaActual = $cliente ? $cliente->tarifa : null;
        $tarifas = \App\Models\Tarifa::all();
        return Inertia::render('interfazUsuario/perfil', [
            'tarifaActual' => $tarifaActual,
            'tarifas' => $tarifas,
            'cliente' => $cliente,
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }


    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:clientes,dni,' . $cliente->dni . ',dni',
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:clientes,email,' . $cliente->dni . ',dni',
        ]);

        $cliente->update($validated);

        // Actualiza también el nombre en users
        if ($user) {
            /** @var \App\Models\User $user  */  // <-- Comentario para que $user->save() no me dé error
            $user->name = $validated['nombre'];
            $user->save();
        }

        return response()->json(['mensaje' => 'Perfil actualizado correctamente.']);
    }

// Muestra la tarifa actual y las opciones disponibles
    public function verTarifa()
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        $tarifas = \App\Models\Tarifa::all();

        if (!$cliente) {
            return Inertia::render('interfazUsuario/miTarifa', [
                'tarifaActual' => null,
                'tarifas' => $tarifas,
                'error' => 'Cliente no encontrado',
            ]);
        }

        return Inertia::render('interfazUsuario/miTarifa', [
            'tarifaActual' => $cliente->tarifa,
            'tarifas' => $tarifas,
            'error' => null,
        ]);
    }

    // Cambiar la tarifa
    public function cambiarTarifa(Request $request)
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $request->validate([
            'id_tarifa' => 'required|exists:tarifas,id_tarifa',
        ]);

        $cliente->id_tarifa = $request->id_tarifa;
        $cliente->save();


        return redirect()->back()->with('success', 'Tarifa actualizada correctamente.');
    }

    public function peliculas(Request $request)
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        $tarifaActual = $cliente ? $cliente->tarifa : null;
        $tarifas = \App\Models\Tarifa::all();

        $search = $request->query('search');

        $peliculas = \App\Models\Pelicula::with('categoria')
            ->when($search, function ($query, $search) {
                return $query->where('titulo', 'like', "%{$search}%")
                    ->orWhere('director', 'like', "%{$search}%")
                    ->orWhere('anio_estreno', 'like', "%{$search}%")
                    ->orWhereHas('categoria', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                    });
            })
            ->get();

        return Inertia::render('interfazUsuario/peliculas', [
            'peliculas' => $peliculas,
            'tarifaActual' => $tarifaActual,
            'tarifas' => $tarifas,
            'search' => $search,
        ]);
    }

    public function misAlquileres()
    {
        $user = Auth::user();
        $cliente = \App\Models\Cliente::where('user_id', $user->id)->first();
        $tarifaActual = $cliente ? $cliente->tarifa : null;
        $tarifas = \App\Models\Tarifa::all();
        $alquileres = $cliente ? \App\Models\Alquiler::with('pelicula')
            ->where('dni_cliente', $cliente->dni)
            ->orderBy('fecha_alquiler', 'desc')
            ->get() : collect();

        return Inertia::render('interfazUsuario/misAlquileres', [
            'alquileres' => $alquileres,
            'tarifaActual' => $tarifaActual,
            'tarifas' => $tarifas,
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

}