<?php

namespace App\Http\Controllers;

use Tightenco\Ziggy\Ziggy;
use App\Models\Pelicula;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;



class PeliculaController extends Controller
{
    // Mostrar lista de películas
    public function index(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $search = $request->query('search');

        $peliculas = Pelicula::when($search, function ($query, $search) {
            return $query->where('titulo', 'like', "%{$search}%")
                ->orWhere('director', 'like', "%{$search}%")
                ->orWhere('anio_estreno', 'like', "%{$search}%")
                ->orWhere('stock', 'like', "%{$search}%")
                ->orWhere('precio', 'like', "%{$search}%")
                ->orWhereHas('categoria', function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%");
                });
        })->with('categoria')->get(); // Parámetro search para la barra de búsqueda

        $categorias = Categoria::all();

        return Inertia::render('peliculas/peliculasIndex', [
            'peliculas' => $peliculas,
            'categorias' => $categorias,
            'search' => $search,
        ]);
    }


    // Mostrar formulario para crear nueva película
    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $categorias = Categoria::all();
        return view('peliculas.create', compact('categorias'));
    }

    // Guardar nueva película en la base de datos
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'director' => 'nullable|string|max:255',
            'anio_estreno' => 'nullable|digits:4|max:' . date('Y'),
            'stock' => 'nullable|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'required|numeric|min:0',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'id_categoria.required' => 'La categoría es obligatoria.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'director.max' => 'El director no puede tener más de 255 caracteres.',
            'anio_estreno.digits' => 'El año de estreno debe tener 4 dígitos.',
            'anio_estreno.max' => 'El año de estreno no puede ser mayor que el actual.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
            'imagen.max' => 'La imagen no puede superar los 2MB.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
        ]);

        $pelicula = new Pelicula($request->all());

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('peliculas', 'public');
            $pelicula->imagen = $rutaImagen;
        }

        $pelicula->save();

        return redirect()->route('peliculas.index')->with('success', 'Película creada correctamente.');
    }

    // Mostrar formulario para editar una película existente
    public function edit(Pelicula $pelicula)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $categorias = Categoria::all();
        return view('peliculas.edit', compact('pelicula', 'categorias'));
    }

    // Actualizar película en la base de datos
    public function update(Request $request, $id)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'director' => 'nullable|string|max:255',
            'anio_estreno' => 'nullable|digits:4|max:' . date('Y'),
            'stock' => 'nullable|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'required|numeric|min:0',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'id_categoria.required' => 'La categoría es obligatoria.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'director.max' => 'El director no puede tener más de 255 caracteres.',
            'anio_estreno.digits' => 'El año de estreno debe tener 4 dígitos.',
            'anio_estreno.max' => 'El año de estreno no puede ser mayor que el actual.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
            'imagen.max' => 'La imagen no puede superar los 2MB.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
        ]);

        $pelicula = Pelicula::findOrFail($id);
        $pelicula->titulo = $request->titulo;
        $pelicula->id_categoria = $request->id_categoria;
        $pelicula->director = $request->director;
        $pelicula->anio_estreno = $request->anio_estreno;
        $pelicula->stock = $request->stock;
        $pelicula->precio = $request->precio;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('peliculas', 'public');
            $pelicula->imagen = $rutaImagen;
        }

        $pelicula->save();

        return redirect()->route('peliculas.index')->with('success', 'Película actualizada correctamente.');
    }

    // Eliminar una película de la base de datos
    public function destroy(Pelicula $pelicula)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $pelicula->delete();
        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }

    public function descargaPDF()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        // Obtener los datos que se mostrarán en el PDF
        $peliculas = Pelicula::all();
        $categorias = Categoria::all();

        // Configuración de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarPeliculas', compact('peliculas', 'categorias'))->render();

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('peliculas.pdf');
    }

    public function show($id)
    {
        return redirect()->route('peliculas.index');
    }

    public function descargarCSV()
    {
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permiso para acceder aquí.');
        }
        $peliculas = Pelicula::with('categoria')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="peliculas.csv"',
        ];

        $callback = function() use ($peliculas) {
            // Escribir BOM para UTF-8
            echo chr(239) . chr(187) . chr(191);
            $handle = fopen('php://output', 'w');
            // Cabecera
            fputcsv($handle, ['Id', 'Título', 'Categoría', 'Director', 'Año', 'Stock', 'Precio'], ';');
            // Datos
            foreach ($peliculas as $p) {
                fputcsv($handle, [
                    $p->id_pelicula,
                    $p->titulo,
                    $p->categoria ? $p->categoria->nombre : '',
                    $p->director,
                    $p->anio_estreno,
                    $p->stock,
                    $p->precio,
                ], ';'); // <-- Punto y coma para separar los campos
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, 'peliculas.csv', $headers);
    }
}