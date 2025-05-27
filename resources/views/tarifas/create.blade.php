@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agregar Nueva Tarifa</h1>
        <form action="{{ route('tarifas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="descuento">Descuento (%)</label>
                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="{{ old('descuento') }}" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('tarifas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection