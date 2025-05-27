@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Película</h1>
    <form action="{{ route('peliculas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select class="form-control" id="id_categoria" name="id_categoria" required>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="director" class="form-label">Director</label>
            <input type="text" class="form-control" id="director" name="director">
        </div>
        <div class="mb-3">
            <label for="anio_estreno" class="form-label">Año de Estreno</label>
            <input type="number" class="form-control" id="anio_estreno" name="anio_estreno" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="0">
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="0.00" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('peliculas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection