@extends('layouts.app')

@section('content')
<h1>Alquileres</h1>

<table class="table">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Pelicula</th>
            <th>Fecha Alquiler</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
            <th>Precio Rebajado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alquileres as $alquiler)
        <tr>
            <td>{{ $alquiler->cliente->nombre }}</td>
            <td>{{ $alquiler->pelicula->titulo }}</td>
            <td>{{ $alquiler->fecha_alquiler }}</td>
            <td>{{ $alquiler->fecha_devolucion }}</td>
            <td>{{ $alquiler->estado }}</td>
            <td>{{ $alquiler->precio_rebajado ? '€' . number_format($alquiler->precio_rebajado, 2) : 'No rebajado' }}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-success btn-sm btn-edit" data-id="{{ $alquiler->id_alquiler }}"
                        data-dni_cliente="{{ $alquiler->dni_cliente }}" data-id_pelicula="{{ $alquiler->id_pelicula }}"
                        data-fecha_alquiler="{{ $alquiler->fecha_alquiler }}" data-fecha_devolucion="{{ $alquiler->fecha_devolucion }}"
                        data-estado="{{ $alquiler->estado }}" data-precio_rebajado="{{ $alquiler->precio_rebajado }}"
                        data-bs-toggle="modal" data-bs-target="#editModal">
                        Editar
                    </button>
                    <form action="{{ route('alquileres.destroy', $alquiler->id_alquiler) }}" method="POST"
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
    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Alquiler</button>
    <a href="{{ url('/') }}" class="btn btn-secondary">Volver al inicio</a>
    <a href="{{ route('descargarPDF.descargarAlquileres') }}" class="btn btn-warning me-4" style="background-color: #f0ad4e; border-color: #eea236; margin-left: 8px;">Descargar listado</a> <!-- Botón para descargar el PDF -->
</div>

<!-- Modal para agregar un nuevo alquiler -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Alquiler</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST" action="{{ route('alquileres.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="addCliente">Cliente</label>
                        <select class="form-control" id="addCliente" name="dni_cliente" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->dni }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addPelicula">Película</label>
                        <select class="form-control" id="addPelicula" name="id_pelicula" required>
                            @foreach($peliculas as $pelicula)
                                <option value="{{ $pelicula->id_pelicula }}">{{ $pelicula->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addFechaAlquiler">Fecha Alquiler</label>
                        <input type="date" class="form-control" id="addFechaAlquiler" name="fecha_alquiler" required>
                    </div>
                    <div class="form-group">
                        <label for="addFechaDevolucion">Fecha Devolución</label>
                        <input type="date" class="form-control" id="addFechaDevolucion" name="fecha_devolucion">
                    </div>
                    <div class="form-group">
                        <label for="addEstado">Estado</label>
                        <select class="form-control" id="addEstado" name="estado" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Devuelto">Devuelto</option>
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
                <h5 class="modal-title" id="editModalLabel">Editar Alquiler</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editDniCliente" name="dni_cliente">
                    <input type="hidden" id="editIdPelicula" name="id_pelicula">
                    <div class="form-group">
                        <label for="editCliente">Cliente</label>
                        <input type="text" class="form-control" id="editCliente" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editPelicula">Película</label>
                        <input type="text" class="form-control" id="editPelicula" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editFechaAlquiler">Fecha Alquiler</label>
                        <input type="date" class="form-control" id="editFechaAlquiler" name="fecha_alquiler" >
                    </div>
                    <div class="form-group">
                        <label for="editFechaDevolucion">Fecha Devolución</label>
                        <input type="date" class="form-control" id="editFechaDevolucion" name="fecha_devolucion" >
                    </div>
                    <div class="form-group">
                        <label for="editEstado">Estado</label>
                        <select class="form-control" id="editEstado" name="estado" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Devuelto">Devuelto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editPrecioRebajado">Precio Rebajado</label>
                        <input type="number" step="0.01" class="form-control" id="editPrecioRebajado" name="precio_rebajado" readonly>
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
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        var dni_cliente = $(this).data('dni_cliente');
        var id_pelicula = $(this).data('id_pelicula');
        var fecha_alquiler = $(this).data('fecha_alquiler'); // Formato DD/MM/YYYY
        var fecha_devolucion = $(this).data('fecha_devolucion'); // Formato DD/MM/YYYY
        var estado = $(this).data('estado');
        var precio_rebajado = $(this).data('precio_rebajado');

        // Función para convertir fechas DD/MM/YYYY a YYYY-MM-DD
        function convertirFecha(formatoCorto) {
            if (!formatoCorto) return ''; // Manejar valores nulos o indefinidos
            var partes = formatoCorto.split('/');
            return `${partes[2]}-${partes[1]}-${partes[0]}`; // Reordenar a YYYY-MM-DD
        }

        // Convertir fechas
        var fechaAlquilerISO = convertirFecha(fecha_alquiler);
        var fechaDevolucionISO = convertirFecha(fecha_devolucion);

        $('#editDniCliente').val(dni_cliente);
        $('#editIdPelicula').val(id_pelicula);
        $('#editCliente').val($(this).closest('tr').find('td:eq(0)').text());
        $('#editPelicula').val($(this).closest('tr').find('td:eq(1)').text());
        $('#editFechaAlquiler').val(fechaAlquilerISO);
        $('#editFechaDevolucion').val(fechaDevolucionISO);
        $('#editEstado').val(estado);
        $('#editPrecioRebajado').val(precio_rebajado);
        $('#editForm').attr('action', '/alquileres/' + id);
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
                        text: 'Hubo un problema al intentar guardar los cambios.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar',
                    });
                }
            }
        });
    });

    // Evento para guardar los cambios del formulario de agregar
    $('#addForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras se guarda el nuevo alquiler.',
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
                text: 'El nuevo alquiler se ha guardado correctamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            }).then(() => {
                location.reload(); 
            });
        },
        error: function(xhr) {
            if (xhr.status === 400) {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON.message,
                    icon: 'error',
                    confirmButtonText: 'Cerrar',
                });
            } else if (xhr.status === 422) {
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
                    text: 'Hubo un problema al intentar guardar el nuevo alquiler.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar',
                });
            }
        }
    });
});

    // Evento para eliminar un alquiler
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
                            text: 'El alquiler se ha eliminado correctamente.',
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
                            text: 'Hubo un problema al intentar eliminar el alquiler.',
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