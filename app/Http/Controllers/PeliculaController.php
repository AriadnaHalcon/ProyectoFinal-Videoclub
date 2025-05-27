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
    // Método para listar todas las películas
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
        })->with('categoria')->get(); // Parámetro search para la consulta

        $categorias = Categoria::all();

        return Inertia::render('peliculas/peliculasIndex', [
            'peliculas' => $peliculas,
            'categorias' => $categorias,
            'search' => $search,
        ]);
    }


    // Mostrar formulario para crear una nueva película
    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $categorias = Categoria::all();
        return view('peliculas.create', compact('categorias'));
    }

    // Guardar una nueva película en la base de datos
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'director' => 'nullable|string|max:255',
            'anio_estreno' => 'nullable|digits:4',
            'stock' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'required|numeric|min:0',
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

    // Actualizar una película en la base de datos
    public function update(Request $request, $id)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'director' => 'nullable|string|max:255',
            'anio_estreno' => 'nullable|digits:4',
            'stock' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'required|numeric|min:0',
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
        // Obtener los datos que se van a mostrar en el PDF
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
}