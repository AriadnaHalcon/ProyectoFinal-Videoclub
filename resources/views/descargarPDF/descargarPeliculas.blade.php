<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link rel="stylesheet" href="style.css">
     <style>
    body {
    background-color: #FADADD; 
    font-family: 'Poppins', sans-serif;  
    }

    .navbar {
        background-color: #F1C6D1;
    }

    .navbar-brand, .nav-link {
        color: #5A3F50 !important; 
        font-weight: bold;
    }

    .navbar-brand:hover, .nav-link:hover {
        color: #D28B7A !important; 
    }

    img {
        max-width: 100%; 
        height: auto;
        border-radius: 15px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px; 
    }

    .container-welcome {
        background-color: #FFF0F5; /* Color suave pastel */
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
    }

    table {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
        
    }

    table td {
        vertical-align: middle; 
    }

    table th, table td {
        padding: 10px;
        text-align: center;
        height: 50px;  
        vertical-align: middle;
    }

    table th {
        background-color: #F1C6D1; 
        color: #5A3F50;
    }

    table tbody tr {
        background-color: #FFF;
    }

    table tbody tr:hover {
        background-color: #ffb2b2;
    }

    .action-buttons {
        display: flex;
        flex-direction: row; 
        justify-content: center;
        /* align-items: center; */
        gap: 10px; 
        text-align: center;
    }

    .action-buttons button {
        padding: 5px 10px;
        font-size: 14px;
        height: 40px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    button {
        display: inline-flex; 
        margin: 0; 
        height: auto; 
        padding: 5px 10px;
    }

    /* Estilo para los Sweet Alerts y los modals*/
    .swal2-popup {
        font-family: 'Poppins', sans-serif !important;
        background-color: #FADADD;
        color: #5A3F50;
        border-radius: 10px;
        padding: 20px;
    }

    .swal2-title {
        font-weight: bold;
        color: #5A3F50;
    }

    .swal2-confirm {
        background-color: #D28B7A;
        color: white;
        border: none;
        font-weight: bold;
    }

    .swal2-confirm:hover {
        background-color: #F1C6D1;
    }

    .swal2-cancel {
        background-color: #F1C6D1;
        color: #5A3F50;
        border: none;
    }

    .swal2-cancel:hover {
        background-color: #D28B7A;
    }

    .modal-content {
        background-color: #FADADD;
        border-radius: 10px;
        padding: 20px;
        border: none;
    }

    .modal-header {
        background-color: #F1C6D1;
        color: #5A3F50;
        border-bottom: 2px solid #D28B7A;
    }

    .modal-title {
        font-weight: bold;
    }

    .modal-body {
        font-size: 14px;
        color: #5A3F50;
    }

    .modal-footer {
        background-color: #F1C6D1;
        display: flex;
        justify-content: space-between;
    }
    .footer {
        position: fixed;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
    }
    </style>
    <title>DescargarPDF</title>
</head>
<body>
    <div class="header" style="text-align: center;">
        <h1>Videoclub</h1>
    </div>
    <h2 style="text-align: center;">Listado de Películas</h2>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Director</th>
                <th>Año de Estreno</th>
                <th>Stock</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peliculas as $pelicula)
            <tr>
                <td>{{ $pelicula->titulo }}</td>
                <td>{{ $pelicula->categoria->nombre }}</td>
                <td>{{ $pelicula->director }}</td>
                <td>{{ $pelicula->anio_estreno }}</td>
                <td>{{ $pelicula->stock }}</td>
                <td>{{ $pelicula->precio }}</td>  
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>