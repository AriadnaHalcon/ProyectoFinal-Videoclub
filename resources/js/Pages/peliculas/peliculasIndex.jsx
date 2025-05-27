import React, { useState, useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';
import ModalAgregarPelicula from '@/Components/ModalAgregarPelicula';
import ModalEditarPelicula from '@/Components/ModalEditarPelicula';
import AppLayout from '@/Layouts/AppLayout';
import SearchBar from '@/Components/SearchBar';

PeliculasIndex.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function PeliculasIndex({ peliculas: initialPeliculas, categorias, search: initialSearch }) {
  const [peliculas, setPeliculas] = useState(initialPeliculas);
  const [search, setSearch] = useState(initialSearch);

  useEffect(() => {
    setSearch(search || '');
  }, [search]);
  
  useEffect(() => {
    setPeliculas(initialPeliculas); // Actualiza el estado peliculas
    setSearch(initialSearch); // Actualiza el estado search
  }, [initialPeliculas, initialSearch]);

  const handleDelete = (id) => {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('ID de la película a eliminar:', id);
        router.delete(route('peliculas.destroy', id), {
          onSuccess: () => {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'La película se ha eliminado correctamente.',
              icon: 'success',
              timer: 2000,
              showConfirmButton: false,
            });
          },
          onError: () => {
            Swal.fire({
              title: 'Error',
              text: 'Hubo un problema al intentar eliminar la película.',
              icon: 'error',
              confirmButtonText: 'Cerrar',
            });
          },
        });
      }
    });
  };

  const handleEdit = (pelicula) => {
    window.dispatchEvent(new CustomEvent('abrir-modal-editar-pelicula', { detail: pelicula }));
  };

  return (
    <>
      <div className="container mt-4">
        <h1 className="mb-4 display-6">Listado de Películas</h1>

        <SearchBar routeName="peliculas.index" search={search} />

        <table className="table table-bordered table-hover">
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
            {peliculas.map((pelicula) => (
              <tr key={pelicula.id_pelicula}>
                <td>
                  {pelicula.imagen ? (
                    <img
                      src={`/storage/${pelicula.imagen}`}
                      alt={pelicula.titulo}
                      style={{ maxWidth: '100px', maxHeight: '100px' }}
                    />
                  ) : (
                    <span>Sin Imagen</span>
                  )}
                </td>
                <td>{pelicula.titulo}</td>
                <td>{pelicula.categoria?.nombre}</td>
                <td>{pelicula.director}</td>
                <td>{pelicula.anio_estreno}</td>
                <td>{pelicula.stock}</td>
                <td>{pelicula.precio}</td>
                <td>
                  <button
                    className="btn btn-success me-2 mb-2"
                    onClick={() => handleEdit(pelicula)}
                  >
                    Editar
                  </button>
                  <button
                    className="btn btn-danger mb-2"
                    onClick={() => handleDelete(pelicula.id_pelicula)}
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        <div className="d-flex justify-content-start mt-4">
          <button
            id="abrirModalAgregarPelicula"
            type="button"
            className="btn btn-primary me-2 mb-2"
            data-bs-toggle="modal"
            data-bs-target="#addModalPelicula"
          >
            Agregar Película
          </button>

          <Link href="/" className="btn btn-secondary mb-2">
            Volver al inicio
          </Link>

          <a
            href={route('descargarPDF.descargarPeliculas')}
            className="btn btn-warning ms-2 mb-2"
          >
            Descargar listado
          </a>
        </div>

        <ModalAgregarPelicula categorias={categorias} />
        <ModalEditarPelicula categorias={categorias} />
      </div>
    </>
  );
}