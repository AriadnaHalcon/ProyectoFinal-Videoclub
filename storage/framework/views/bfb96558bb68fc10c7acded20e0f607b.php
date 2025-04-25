<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Videoclub'); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('./css/style.css')); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand text-pastel-pink" href="<?php echo e(route('home')); ?>">Videoclub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-pastel-pink" href="<?php echo e(route('clientes.index')); ?>">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-pastel-pink" href="<?php echo e(route('peliculas.index')); ?>">Películas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-pastel-pink" href="<?php echo e(route('alquileres.index')); ?>">Alquileres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-pastel-pink" href="<?php echo e(route('categorias.index')); ?>">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-pastel-pink" href="<?php echo e(route('tarifas.index')); ?>">Tarifas</a>
                        <!-- Enlace a Tarifas -->
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-pastel-pink" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                </form>
                                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html><?php /**PATH C:\Users\Otros\Desktop\PHP\videoclub2\resources\views/layouts/app.blade.php ENDPATH**/ ?>