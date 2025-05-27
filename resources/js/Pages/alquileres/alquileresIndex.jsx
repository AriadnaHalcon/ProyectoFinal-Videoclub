import React from 'react';
import { Link, router } from '@inertiajs/react';
import Swal from 'sweetalert2';
import ModalAgregarAlquiler from '@/components/ModalAgregarAlquiler';
import ModalEditarAlquiler from '@/components/ModalEditarAlquiler';
import AppLayout from '@/Layouts/AppLayout';

AlquileresIndex.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function AlquileresIndex({ alquileres, clientes, peliculas }) {
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
        router.delete(route('alquileres.destroy', id), {
          onSuccess: () => {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'El alquiler se ha eliminado correctamente.',
              icon: 'success',
              timer: 2000,
              showConfirmButton: false,
            });
          },
          onError: () => {
            Swal.fire({
              title: 'Error',
              text: 'Hubo un problema al intentar eliminar el alquiler.',
              icon: 'error',
              confirmButtonText: 'Cerrar',
            });
          },
        });
      }
    });
  };

  const handleEdit = (alquiler) => {
    window.dispatchEvent(new CustomEvent('abrir-modal-editar-alquiler', { detail: alquiler }));
  };

  return (
    
    <div className="container mt-4">
      <h1 className="mb-4 display-6">Listado de Alquileres</h1>

      <table className="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Película</th>
            <th>Fecha Alquiler</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
            <th>Precio Rebajado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {alquileres.map((alquiler) => (
            <tr key={alquiler.id_alquiler}>
              <td>{alquiler.cliente?.nombre}</td>
              <td>{alquiler.pelicula?.titulo}</td>
              <td>{alquiler.fecha_alquiler}</td>
              <td>{alquiler.fecha_devolucion || 'Sin devolver'}</td>
              <td>{alquiler.estado}</td>
              <td>
                {typeof alquiler.precio_rebajado === 'number'
                  ? `€${alquiler.precio_rebajado.toFixed(2)}`
                  : 'Precio no disponible'}
              </td>
              <td>
                <button
                  className="btn btn-success me-2 mb-2"
                  onClick={() => handleEdit(alquiler)}
                >
                  Editar
                </button>
                <button
                  className="btn btn-danger mb-2"
                  onClick={() => handleDelete(alquiler.id_alquiler)}
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
          className="btn btn-primary me-2 mb-2"
          data-bs-toggle="modal"
          data-bs-target="#addModal"
        >
          Agregar Alquiler
        </button>

        <Link href="/" className="btn btn-secondary mb-2">
          Volver al inicio
        </Link>

        <a
          href={route('descargarPDF.descargarAlquileres')}
          className="btn btn-warning ms-2 mb-2"
        >
          Descargar listado
        </a>
      </div>

      <ModalAgregarAlquiler clientes={clientes} peliculas={peliculas} />
      <ModalEditarAlquiler clientes={clientes} peliculas={peliculas} />
    </div>
    
    
  );
}