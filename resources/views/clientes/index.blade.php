@extends('layouts.app')

@section('content')
<!-- Tu código existente para mostrar la lista de clientes -->

<table class="table">
    <thead>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Tarifa</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>{{ $cliente->dni }}</td>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->direccion }}</td>
            <td>{{ $cliente->telefono }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->tarifa->nombre }}</td>
            <td>
                <button class="btn btn-success edit-btn" data-id="{{ $cliente->dni }}">Editar</button>
                <form class="delete-form" action="{{ route('clientes.destroy', $cliente->dni) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Botones debajo de la tabla -->
<div class="d-flex justify-content-start mt-4">
    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Cliente</button>
    <a href="{{ url('/') }}" class="btn btn-secondary">Volver al inicio</a>
    <a href="{{ route('descargarPDF.descargarClientes') }}" class="btn btn-warning me-4" style="background-color: #f0ad4e; border-color: #eea236; margin-left: 8px;">Descargar listado</a> <!-- Botón para descargar el PDF -->
</div>

<!-- Modal para agregar un nuevo cliente -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Cliente</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="addDni">DNI</label>
                        <input type="text" class="form-control" id="addDni" name="dni" required>
                    </div>
                    <div class="form-group">
                        <label for="addNombre">Nombre</label>
                        <input type="text" class="form-control" id="addNombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="addDireccion">Dirección</label>
                        <input type="text" class="form-control" id="addDireccion" name="direccion">
                    </div>
                    <div class="form-group">
                        <label for="addTelefono">Teléfono</label>
                        <input type="text" class="form-control" id="addTelefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="addEmail">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email">
                    </div>
                    <div class="form-group">
                        <label for="addTarifa">Tarifa</label>
                        <select class="form-control" id="addTarifa" name="id_tarifa">
                            @foreach($tarifas as $tarifa)
                                <option value="{{ $tarifa->id_tarifa }}">{{ $tarifa->nombre }}</option>
                            @endforeach
                        </select>
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
                <h5 class="modal-title" id="editModalLabel">Editar Cliente</h5>
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
                        <label for="editDireccion">Dirección</label>
                        <input type="text" class="form-control" id="editDireccion" name="direccion">
                    </div>
                    <div class="form-group">
                        <label for="editTelefono">Teléfono</label>
                        <input type="text" class="form-control" id="editTelefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="form-group">
                        <label for="editTarifa">Tarifa</label>
                        <select class="form-control" id="editTarifa" name="id_tarifa">
                            @foreach($tarifas as $tarifa)
                                <option value="{{ $tarifa->id_tarifa }}">{{ $tarifa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
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
    $('.edit-btn').on('click', function() {
        var clienteId = $(this).data('id');
        $.ajax({
            url: '/clientes/' + clienteId + '/edit',
            method: 'GET',
            success: function(response) {
                console.log(response); // Añadir esta línea para depuración
                $('#editNombre').val(response.nombre);
                $('#editDireccion').val(response.direccion);
                $('#editTelefono').val(response.telefono);
                $('#editEmail').val(response.email);
                $('#editTarifa').val(response.id_tarifa);
                $('#editForm').attr('action', '/clientes/' + clienteId);
                $('#editModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al obtener los datos del cliente.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar',
                });
            }
        });
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
            text: 'Por favor, espera mientras se guarda el nuevo cliente.',
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
                    text: 'El nuevo cliente se ha guardado correctamente.',
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
                        text: 'Hubo un problema al intentar guardar el nuevo cliente.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar',
                    });
                }
            }
        });
    });

    // Evento para eliminar un cliente
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
                            text: 'El cliente se ha eliminado correctamente.',
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
                            text: 'Hubo un problema al intentar eliminar el cliente.',
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