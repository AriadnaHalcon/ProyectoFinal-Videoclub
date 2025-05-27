import React, { useRef } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalAgregarAlquiler({ clientes, peliculas }) {
  const modalRef = useRef(null);
  const { data, setData, post, reset, processing, errors } = useForm({
    dni_cliente: clientes.length ? clientes[0].dni : '',
    id_pelicula: peliculas.length ? peliculas[0].id_pelicula : '',
    fecha_alquiler: '',
    fecha_devolucion: '',
    estado: 'Pendiente',
  });

  const handleSubmit = (e) => {
    e.preventDefault();

    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guarda el alquiler.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    post(route('alquileres.store'), {
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'El alquiler se ha guardado correctamente.',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false,
        });

        reset();

        // Cerrar el modal de forma segura
        const modalInstance = bootstrap.Modal.getInstance(modalRef.current);
        if (modalInstance) {
          modalInstance.hide();
        }

        // Esperar a que termine la animación y limpiar backdrop
        setTimeout(() => {
          document.querySelectorAll('.modal-backdrop').forEach((b) => b.remove());
          document.body.classList.remove('modal-open');
          document.body.style.overflow = '';
        }, 300);
      },
      onError: () => {
        Swal.fire({
          title: 'Error',
          text: 'Hubo un problema al guardar el alquiler.',
          icon: 'error',
          confirmButtonText: 'Cerrar',
        });
      },
    });
  };

  return (
    <div
      ref={modalRef}
      className="modal fade"
      id="addModal"
      tabIndex="-1"
      aria-labelledby="addModalLabel"
      aria-hidden="true"
    >
      <div className="modal-dialog">
        <form onSubmit={handleSubmit} className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="addModalLabel">Agregar Alquiler</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            <div className="mb-3">
              <label className="form-label">Cliente</label>
              <select
                className="form-control"
                value={data.dni_cliente}
                onChange={(e) => setData('dni_cliente', e.target.value)}
              >
                {clientes.map((cliente) => (
                  <option key={cliente.dni} value={cliente.dni}>
                    {cliente.nombre}
                  </option>
                ))}
              </select>
              {errors.dni_cliente && <div className="text-danger">{errors.dni_cliente}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Película</label>
              <select
                className="form-control"
                value={data.id_pelicula}
                onChange={(e) => setData('id_pelicula', e.target.value)}
              >
                {peliculas.map((pelicula) => (
                  <option key={pelicula.id_pelicula} value={pelicula.id_pelicula}>
                    {pelicula.titulo}
                  </option>
                ))}
              </select>
              {errors.id_pelicula && <div className="text-danger">{errors.id_pelicula}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Fecha Alquiler</label>
              <input
                type="date"
                className="form-control"
                value={data.fecha_alquiler}
                onChange={(e) => setData('fecha_alquiler', e.target.value)}
              />
              {errors.fecha_alquiler && <div className="text-danger">{errors.fecha_alquiler}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Fecha Devolución</label>
              <input
                type="date"
                className="form-control"
                value={data.fecha_devolucion}
                onChange={(e) => setData('fecha_devolucion', e.target.value)}
              />
              {errors.fecha_devolucion && <div className="text-danger">{errors.fecha_devolucion}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Estado</label>
              <select
                className="form-control"
                value={data.estado}
                onChange={(e) => setData('estado', e.target.value)}
              >
                <option value="Pendiente">Pendiente</option>
                <option value="Devuelto">Devuelto</option>
              </select>
              {errors.estado && <div className="text-danger">{errors.estado}</div>}
            </div>
          </div>
          <div className="modal-footer">
            <button type="submit" className="btn btn-primary" disabled={processing}>
              Guardar
            </button>
            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}