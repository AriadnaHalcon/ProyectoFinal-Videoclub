<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class PeliculaController extends Controller
{
    // Método para listar todas las películas
    public function index(Request $request)
    {
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
        })->get();
        
        $categorias = Categoria::all(); // Obtén las categorías
        return view('peliculas.index', compact('peliculas', 'categorias'));
    }

    // Mostrar formulario para crear una nueva película
    public function create()
    {
        $categorias = Categoria::all();
        return view('peliculas.create', compact('categorias'));
    }

    // Guardar una nueva película en la base de datos
    public function store(Request $request)
    {
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
        $categorias = Categoria::all();
        return view('peliculas.edit', compact('pelicula', 'categorias'));
    }

    // Actualizar una película en la base de datos
    public function update(Request $request, $id)
    {
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
        $pelicula->delete();
        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }

    public function descargaPDF()
    {
        // Obtener los datos que deseas mostrar en el PDF
        $peliculas = Pelicula::all();
        $categorias = Categoria::all(); // Obtén las categorías

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarPeliculas', compact('peliculas', 'categorias'))->render();
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // (Opcional) Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('peliculas.pdf');
    }

    public function show($id)
    {
        // Si no necesitas este método, simplemente puedes redirigir o retornar una vista vacía.
        return redirect()->route('peliculas.index');
    }
}