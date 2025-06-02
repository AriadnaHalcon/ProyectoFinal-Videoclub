import React, { useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalEditarAlquiler({ clientes, peliculas }) {
  const { data, setData, put, reset, processing, errors } = useForm({
    dni_cliente: '',
    id_pelicula: '',
    fecha_alquiler: '',
    fecha_devolucion: '',
    estado: '',
  });

  const [id, setId] = useState(null);

  useEffect(() => {
    const handler = (e) => {
      const alquiler = e.detail;
      console.log('Alquiler recibido:', alquiler);
      setId(alquiler.id_alquiler);
      setData({
        dni_cliente: alquiler.cliente?.dni || '',
        id_pelicula: alquiler.pelicula?.id_pelicula || '',
        fecha_alquiler: alquiler.fecha_alquiler || '',
        fecha_devolucion: alquiler.fecha_devolucion || '',
        estado: alquiler.estado || 'Pendiente',
      });

      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    };

    window.addEventListener('abrir-modal-editar-alquiler', handler);
    return () => window.removeEventListener('abrir-modal-editar-alquiler', handler);
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!id) return;
  
    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guardan los cambios.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  
    put(route('alquileres.update', id), {
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'Los cambios se han guardado correctamente.',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false,
        });
        reset();
        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        modal.hide();
      },
      onError: () => {
        Swal.fire({
          title: 'Error',
          text: 'Hubo un problema al guardar los cambios.',
          icon: 'error',
          confirmButtonText: 'Cerrar',
        });
      },
    });
  };

  return (
    <div className="modal fade" id="editModal" tabIndex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div className="modal-dialog">
        <form onSubmit={handleSubmit} className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="editModalLabel">Editar Alquiler</h5>
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
              Guardar cambios
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