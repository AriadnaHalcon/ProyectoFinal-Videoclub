import React, { useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';
import ModalAgregarTarifa from '@/Components/ModalAgregarTarifa';
import ModalEditarTarifa from '@/Components/ModalEditarTarifa';
import AppLayout from '@/Layouts/AppLayout';

TarifasIndex.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function TarifasIndex({ tarifas, error, success }) {
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
        router.visit('/tarifas'); 
      });
    }

    if (error) {
      Swal.fire({
        title: 'Error',
        text: error,
        icon: 'error',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        router.visit('/tarifas'); 
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
        router.delete(route('tarifas.destroy', id));
      }
    });
  };

  const handleEdit = (tarifa) => {
    window.dispatchEvent(new CustomEvent('abrir-modal-editar-tarifa', { detail: tarifa }));
  };

  return (
    <>
      <div className="container mt-4">
        <h1 className="mb-4 display-6">Listado de Tarifas</h1>

        <table className="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descuento</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {tarifas.map((tarifa) => (
              <tr key={tarifa.id_tarifa}>
                <td>{tarifa.id_tarifa}</td>
                <td>{tarifa.nombre}</td>
                <td>{tarifa.descuento}</td>
                <td>
                  <div>
                    <button
                      className="btn btn-success me-2 mb-2"
                      onClick={() => handleEdit(tarifa)}
                    >
                      Editar
                    </button>
                    <button
                      className="btn btn-danger mb-2"
                      onClick={() => handleDelete(tarifa.id_tarifa)}
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
            Agregar Tarifa
          </button>

          <Link href="/" className="btn btn-secondary mb-2">
            Volver al inicio
          </Link>

          <a
            href={route('descargarPDF.descargarTarifas')}
            className="btn btn-warning ms-2 mb-2"
          >
            Descargar listado
          </a>
        </div>

        <ModalAgregarTarifa />
        <ModalEditarTarifa />
      </div>
    </>
  );
}