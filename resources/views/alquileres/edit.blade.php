@extends('layouts.app')

@section('content')
    <h1>Editar Alquiler</h1>

    <form action="{{ route('alquileres.update', $alquiler) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="dni_cliente">Cliente</label>
            <select name="dni_cliente" id="dni_cliente" class="form-control">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->dni }}" @if($cliente->dni == $alquiler->dni_cliente) selected @endif>{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_pelicula">Película</label>
            <select name="id_pelicula" id="id_pelicula" class="form-control">
                @foreach($peliculas as $pelicula)
                    <option value="{{ $pelicula->id_pelicula }}" @if($pelicula->id_pelicula == $alquiler->id_pelicula) selected @endif>{{ $pelicula->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_alquiler">Fecha Alquiler</label>
            <input type="date" name="fecha_alquiler" id="fecha_alquiler" value="{{ $alquiler->fecha_alquiler }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="fecha_devolucion">Fecha Devolución</label>
            <input type="date" name="fecha_devolucion" id="fecha_devolucion" value="{{ $alquiler->fecha_devolucion }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value="Pendiente" @if($alquiler->estado == 'Pendiente') selected @endif>Pendiente</option>
                <option value="Devuelto" @if($alquiler->estado == 'Devuelto') selected @endif>Devuelto</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('alquileres.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection