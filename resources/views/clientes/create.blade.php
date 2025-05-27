@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Cliente</h1>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="id_tarifa">Tarifa</label>
            <select class="form-control" id="id_tarifa" name="id_tarifa">
                <option value="">Sin Tarifa</option>
                @foreach($tarifas as $tarifa)
                    <option value="{{ $tarifa->id_tarifa }}" @if(old('id_tarifa', $cliente->id_tarifa ?? '') == $tarifa->id_tarifa) selected @endif>
                        {{ $tarifa->nombre }} ({{ $tarifa->descuento }}% descuento)
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection