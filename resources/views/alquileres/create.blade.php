@extends('layouts.app')

@section('content')
    <h1>Nuevo Alquiler</h1>

    <form action="{{ route('alquileres.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="dni_cliente">Cliente</label>
            <select name="dni_cliente" id="dni_cliente" class="form-control">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->dni }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_pelicula">Película</label>
            <select name="id_pelicula" id="id_pelicula" class="form-control">
                @foreach($peliculas as $pelicula)
                    <option value="{{ $pelicula->id_pelicula }}">{{ $pelicula->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_alquiler">Fecha Alquiler</label>
            <input type="date" name="fecha_alquiler" id="fecha_alquiler" class="form-control">
        </div>

        <div class="form-group">
            <label for="fecha_devolucion">Fecha Devolución</label>
            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control">
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value="Pendiente">Pendiente</option>
                <option value="Devuelto">Devuelto</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection