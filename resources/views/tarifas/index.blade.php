@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Tarifas</h1>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID Tarifa</th>
                <th>Nombre</th>
                <th>Descuento (%)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tarifas as $tarifa)
            <tr>
                <td>{{ $tarifa->id_tarifa }}</td>
                <td>{{ $tarifa->nombre }}</td>
                <td>{{ $tarifa->descuento }}%</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-success btn-sm btn-edit" data-id="{{ $tarifa->id_tarifa }}"
                            data-nombre="{{ $tarifa->nombre }}" data-descuento="{{ $tarifa->descuento }}"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            Editar
                        </button>
                        <form action="{{ route('tarifas.destroy', $tarifa->id_tarifa) }}" method="POST"
                            class="delete-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botones debajo de la tabla -->
    <div class="d-flex justify-content-start mt-4">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Tarifa</button>
        <a href="{{ url('/') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('descargarPDF.descargarTarifas') }}" class="btn btn-warning me-4" style="background-color: #f0ad4e; border-color: #eea236; margin-left: 8px;">Descargar listado</a> <!-- Botón para descargar el PDF -->
    </div>

    <!-- Modal para agregar una nueva tarifa -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Agregar Tarifa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST" action="{{ route('tarifas.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="addNombre">Nombre</label>
                            <input type="text" class="form-control" id="addNombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="addDescuento">Descuento (%)</label>
                            <input type="number" class="form-control" id="addDescuento" name="descuento" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edición -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Tarifa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editNombre">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescuento">Descuento (%)</label>
                            <input type="number" class="form-control" id="editDescuento" name="descuento" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluir Bootstrap JS (opcional, si usas Bootstrap para el modal) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Evento para abrir el modal de edición
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descuento = $(this).data('descuento');

        $('#editNombre').val(nombre);
        $('#editDescuento').val(descuento);
        $('#editForm').attr('action', '/tarifas/' + id);
        $('#editModal').modal('show');
    });

    // Evento para guardar los cambios del formulario de edición
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor, espera mientras se guardan los cambios.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    title: '¡Guardado!',
                    text: 'Los cambios se han guardado correctamente.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload(); 
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al intentar guardar los cambios.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar',
                });
            }
        });
    });

    // Evento para guardar los cambios del formulario de agregar
    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor, espera mientras se guarda la nueva tarifa.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    title: '¡Guardado!',
                    text: 'La nueva tarifa se ha guardado correctamente.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload(); 
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });
                    Swal.fire({
                        title: 'Error de validación',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Cerrar',
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al intentar guardar la nueva tarifa.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar',
                    });
                }
            }
        });
    });

    // Evento para eliminar una tarifa
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (response) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'La tarifa se ha eliminado correctamente.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al intentar eliminar la tarifa.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar',
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection