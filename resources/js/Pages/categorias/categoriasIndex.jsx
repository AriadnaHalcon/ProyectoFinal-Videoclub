import React, { useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';
import ModalAgregarCategoria from '@/Components/ModalAgregarCategoria';
import ModalEditarCategoria from '@/Components/ModalEditarCategoria';
import AppLayout from '@/Layouts/AppLayout';

CategoriasIndex.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function CategoriasIndex({ categorias, error, success }) {
  const { flash } = usePage().props;

  useEffect(() => {
    if (success) {
      Swal.fire({
        title: '¡Éxito!',
        text: success,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        router.visit('/categorias'); 
      });
    }

    if (error) {
      Swal.fire({
        title: 'Error',
        text: error,
        icon: 'error',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        router.visit('/categorias'); 
      });
    }
  }, [success, error]);

  const handleDelete = (id) => {
    console.log('handleDelete ejecutado para ID:', id);

    // Close Bootstrap modals if open
    document.querySelectorAll('.modal').forEach(modal => {
      bootstrap.Modal.getInstance(modal)?.hide();
    });

    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('Eliminación confirmada');
        router.delete(route('categorias.destroy', id));
      }
    });
  };

  const handleEdit = (categoria) => {
    window.dispatchEvent(new CustomEvent('abrir-modal-editar-categoria', { detail: categoria }));
  };

  return (
    <>
      <div className="container mt-4">
        <h1 className="mb-4 display-6">Listado de Categorías</h1>

        <table className="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {categorias.map((categoria) => (
              <tr key={categoria.id_categoria}>
                <td>{categoria.id_categoria}</td>
                <td>{categoria.nombre}</td>
                <td>{categoria.descripcion}</td>
                <td>
                  <div>
                    <button
                      className="btn btn-success me-2 mb-2"
                      onClick={() => handleEdit(categoria)}
                    >
                      Editar
                    </button>
                    <button
                      className="btn btn-danger mb-2"
                      onClick={() => handleDelete(categoria.id_categoria)}
                    >
                      Eliminar
                    </button>
                  </div>
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
            Agregar Categoría
          </button>

          <Link href="/" className="btn btn-secondary mb-2">
            Volver al inicio
          </Link>

          <a
            href={route('descargarPDF.descargarCategorias')}
            className="btn btn-warning ms-2 mb-2"
          >
            Descargar listado
          </a>
        </div>

        <ModalAgregarCategoria />
        <ModalEditarCategoria />
      </div>
    </>
  );
}