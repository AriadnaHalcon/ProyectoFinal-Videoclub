@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Tarifa</h1>
        <form action="{{ route('tarifas.update', $tarifa->id_tarifa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $tarifa->nombre) }}" required>
            </div>

            <div class="form-group">
                <label for="descuento">Descuento (%)</label>
                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="{{ old('descuento', $tarifa->descuento) }}" required>
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('tarifas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection