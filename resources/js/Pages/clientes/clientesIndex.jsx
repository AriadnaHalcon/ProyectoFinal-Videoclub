import React from 'react';
import { Link, router } from '@inertiajs/react';
import Swal from 'sweetalert2';
import ModalAgregarCliente from '@/components/ModalAgregarCliente';
import ModalEditarCliente from '@/components/ModalEditarCliente';
import AppLayout from '@/Layouts/AppLayout';

ClientesIndex.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function ClientesIndex({ clientes, tarifas }) {
  const handleDelete = (dni) => {
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
        router.delete(route('clientes.destroy', dni), {
          onSuccess: () => {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'El cliente se ha eliminado correctamente.',
              icon: 'success',
              timer: 2000,
              showConfirmButton: false,
            });
          },
          onError: () => {
            Swal.fire({
              title: 'Error',
              text: 'Hubo un problema al intentar eliminar el cliente.',
              icon: 'error',
              confirmButtonText: 'Cerrar',
            });
          },
        });
      }
    });
  };

  const handleEdit = (cliente) => {
    window.dispatchEvent(new CustomEvent('abrir-modal-editar', { detail: cliente }));
  };

return (
    <>
        <div className="container mt-4">
            <h1 className="mb-4 display-6">Listado de Clientes</h1>

            <table className="table table-bordered table-hover">
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
                    {clientes.map((cliente) => (
                        <tr key={cliente.dni}>
                            <td>{cliente.dni}</td>
                            <td>{cliente.nombre}</td>
                            <td>{cliente.direccion}</td>
                            <td>{cliente.telefono}</td>
                            <td>{cliente.email}</td>
                            <td>{cliente.tarifa?.nombre}</td>
                            <td>
                                <button
                                    className="btn btn-success me-2 mb-2"
                                    onClick={() => handleEdit(cliente)}
                                >
                                    Editar
                                </button>
                                <button
                                    className="btn btn-danger mb-2"
                                    onClick={() => handleDelete(cliente.dni)}
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            {/* Botones debajo de la tabla */}
            <div className="d-flex justify-content-start mt-4">
                <button
                    id="abrirModalAgregarCliente"
                    type="button"
                    className="btn btn-primary me-2 mb-2"
                    data-bs-toggle="modal"
                    data-bs-target="#addModal"
                >
                    Agregar Cliente
                </button>

                <Link href="/" className="btn btn-secondary mb-2">
                    Volver al inicio
                </Link>

            <a
                    href={route('descargarPDF.descargarClientes')}
                    className="btn btn-warning ms-2 mb-2"
            >
                    Descargar listado
            </a>
            </div>
        </div>
        <ModalAgregarCliente tarifas={tarifas} />
        <ModalEditarCliente tarifas={tarifas} />
    </>
);
}