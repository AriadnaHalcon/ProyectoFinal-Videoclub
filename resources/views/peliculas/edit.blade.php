@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Película</h1>
        <form action="{{ route('peliculas.update', $pelicula->id_pelicula) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            <div class="form-group">
                <label for="imagen">Imagen</label>
                @if($pelicula->imagen)
                    <p>Imagen actual:</p>
                    <img src="{{ asset('storage/' . $pelicula->imagen) }}" alt="Imagen de {{ $pelicula->titulo }}" width="200">
                @endif
                <input type="file" name="imagen" class="form-control" accept="image/*">
            </div> 
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $pelicula->titulo) }}" required>
            </div>

            <div class="form-group">
                <label for="id_categoria">Categoría</label>
                <select class="form-control" id="id_categoria" name="id_categoria" required>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}" @if($pelicula->id_categoria == $categoria->id_categoria) selected @endif>{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="director">Director</label>
                <input type="text" class="form-control" id="director" name="director" value="{{ old('director', $pelicula->director) }}">
            </div>

            <div class="form-group">
                <label for="anio_estreno">Año de Estreno</label>
                <input type="number" class="form-control" id="anio_estreno" name="anio_estreno" value="{{ old('anio_estreno', $pelicula->anio_estreno) }}">
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $pelicula->stock) }}">
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="{{ old('precio', $pelicula->precio) }}" required>            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection