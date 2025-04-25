

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Películas</h1>
    <form method="GET" action="<?php echo e(route('peliculas.index')); ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por filtro..." value="<?php echo e(request()->query('search')); ?>">
            <button type="submit" class="btn btn-primary" style="background-color:rgb(235, 138, 162); border-color: #fc6e6e;">Buscar</button>
            <a href="<?php echo e(route('peliculas.index')); ?>" class="btn btn-secondary ms-2">Limpiar</a>
        </div>
    </form>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Director</th>
                <th>Año de Estreno</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $peliculas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pelicula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if($pelicula->imagen): ?>
                    <img src="<?php echo e(asset('storage/' . $pelicula->imagen)); ?>" alt="<?php echo e($pelicula->titulo); ?>"
                        style="max-width: 100px; max-height: 100px;">
                    <?php else: ?>
                    <span>Sin Imagen</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($pelicula->titulo); ?></td>
                <td><?php echo e($pelicula->categoria->nombre); ?></td>
                <td><?php echo e($pelicula->director); ?></td>
                <td><?php echo e($pelicula->anio_estreno); ?></td>
                <td><?php echo e($pelicula->stock); ?></td>
                <td><?php echo e($pelicula->precio); ?></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-success btn-sm btn-edit" data-id="<?php echo e($pelicula->id_pelicula); ?>"
                            data-titulo="<?php echo e($pelicula->titulo); ?>" data-categoria="<?php echo e($pelicula->id_categoria); ?>"
                            data-director="<?php echo e($pelicula->director); ?>" data-anio_estreno="<?php echo e($pelicula->anio_estreno); ?>"
                            data-stock="<?php echo e($pelicula->stock); ?>" data-precio="<?php echo e($pelicula->precio); ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            Editar
                        </button>
                        <form action="<?php echo e(route('peliculas.destroy', $pelicula->id_pelicula)); ?>" method="POST"
                            class="delete-form" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Botones debajo de la tabla -->
    <div class="d-flex justify-content-start mt-4">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Película</button>
        <a href="<?php echo e(url('/')); ?>" class="btn btn-secondary">Volver al inicio</a>
        <a href="<?php echo e(route('descargarPDF.descargarPeliculas')); ?>" class="btn btn-warning me-4" style="background-color: #f0ad4e; border-color: #eea236; margin-left: 8px;">Descargar listado</a> <!-- Botón para descargar el PDF -->
    </div>

    <!-- Modal para agregar una nueva película -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Agregar Película</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST" action="<?php echo e(route('peliculas.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="addTitulo">Título</label>
                            <input type="text" class="form-control" id="addTitulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="addCategoria">Categoría</label>
                            <select class="form-control" id="addCategoria" name="id_categoria" required>
                                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id_categoria); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addDirector">Director</label>
                            <input type="text" class="form-control" id="addDirector" name="director">
                        </div>
                        <div class="form-group">
                            <label for="addAnioEstreno">Año de Estreno</label>
                            <input type="number" class="form-control" id="addAnioEstreno" name="anio_estreno">
                        </div>
                        <div class="form-group">
                            <label for="addStock">Stock</label>
                            <input type="number" class="form-control" id="addStock" name="stock">
                        </div>
                        <div class="form-group">
                            <label for="addPrecio">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="addPrecio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="addImagen">Imagen</label>
                            <input type="file" class="form-control" id="addImagen" name="imagen">
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
                    <h5 class="modal-title" id="editModalLabel">Editar Película</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="form-group">
                            <label for="editTitulo">Título</label>
                            <input type="text" class="form-control" id="editTitulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="editCategoria">Categoría</label>
                            <select class="form-control" id="editCategoria" name="id_categoria" required>
                                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id_categoria); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDirector">Director</label>
                            <input type="text" class="form-control" id="editDirector" name="director">
                        </div>
                        <div class="form-group">
                            <label for="editAnioEstreno">Año de Estreno</label>
                            <input type="number" class="form-control" id="editAnioEstreno" name="anio_estreno">
                        </div>
                        <div class="form-group">
                            <label for="editStock">Stock</label>
                            <input type="number" class="form-control" id="editStock" name="stock">
                        </div>
                        <div class="form-group">
                            <label for="editPrecio">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="editPrecio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="editImagen">Imagen</label>
                            <input type="file" class="form-control" id="editImagen" name="imagen">
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
        var titulo = $(this).data('titulo');
        var categoria = $(this).data('categoria');
        var director = $(this).data('director');
        var anio_estreno = $(this).data('anio_estreno');
        var stock = $(this).data('stock');
        var precio = $(this).data('precio');

        $('#editTitulo').val(titulo);
        $('#editCategoria').val(categoria);
        $('#editDirector').val(director);
        $('#editAnioEstreno').val(anio_estreno);
        $('#editStock').val(stock);
        $('#editPrecio').val(precio);
        $('#editForm').attr('action', '/peliculas/' + id);
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

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: formData,
        contentType: false,
        processData: false,
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
                    title: 'Error',
                    html: errorMessage,
                    icon: 'error',
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los cambios.',
                    icon: 'error',
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
            text: 'Por favor, espera mientras se guarda la nueva película.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    title: '¡Guardado!',
                    text: 'La nueva película se ha guardado correctamente.',
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
                        text: 'Hubo un problema al intentar guardar la nueva película.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar',
                    });
                }
            }
        });
    });

    // Evento para eliminar una película
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
                            text: 'La película se ha eliminado correctamente.',
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
                            text: 'Hubo un problema al intentar eliminar la película.',
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Otros\Desktop\PHP\videoclub2\resources\views/peliculas/index.blade.php ENDPATH**/ ?>