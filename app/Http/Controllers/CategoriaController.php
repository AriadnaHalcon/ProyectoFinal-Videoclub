<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $categorias = Categoria::all();
        return Inertia::render('categorias/categoriasIndex', [
            'categorias' => $categorias,
        ]);
    }

    public function create()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'nombre' => 'required|unique:categorias',
            'descripcion' => 'nullable',
        ]);

        Categoria::create($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre,' . $categoria->id_categoria . ',id_categoria',
            'descripcion' => 'nullable',
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        try {
            if ($categoria->peliculas()->exists()) {
                return Inertia::render('categorias/categoriasIndex', [
                    'categorias' => Categoria::all(),
                    'error' => 'No se puede eliminar una categoría asignada a una película.',
                ]);
            } else {
                $categoria->delete();
                return Inertia::render('categorias/categoriasIndex', [
                    'categorias' => Categoria::all(),
                    'success' => 'Categoría eliminada correctamente.',
                ]);
            }
        } catch (\Exception $e) {
            return Inertia::render('categorias/categoriasIndex', [
                'categorias' => Categoria::all(),
                'error' => 'Error al eliminar la categoría.',
            ]);
        }
    }
    

    public function descargaPDF()
    {
        if (Auth::user()->rol !== 'administrador') {
        abort(403, 'No tienes permiso para acceder aquí.');
        }
        $categorias = Categoria::all();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Renderizar la vista en HTML
        $pdfContent = view('descargarPDF.descargarCategorias', compact('categorias'))->render();
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($pdfContent);

        // Configurar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('categorias.pdf');
    }
}
