import React, { useRef, useState } from 'react';
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

  const [fechaError, setFechaError] = React.useState('');
  const [clienteInput, setClienteInput] = useState('');
  const [clienteSuggestions, setClienteSuggestions] = useState([]);
  const [peliculaInput, setPeliculaInput] = useState('');
  const [peliculaSuggestions, setPeliculaSuggestions] = useState([]);

  const handleSubmit = (e) => {
    e.preventDefault();
    setFechaError('');
    if (data.fecha_alquiler && data.fecha_devolucion && data.fecha_alquiler > data.fecha_devolucion) {
      setFechaError('La fecha de devolución debe ser igual o posterior a la fecha de alquiler.');
      return;
    }

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

        // Limpiar el modal y eliminar el backdrop
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

  // Autocompletado de cliente
  const handleClienteInput = (e) => {
    const value = e.target.value;
    setClienteInput(value);
    setData('dni_cliente', '');
    if (value.length === 0) {
      setClienteSuggestions([]);
      return;
    }
    const filtered = clientes.filter(c =>
      c.nombre.toLowerCase().includes(value.toLowerCase()) ||
      c.dni.toLowerCase().includes(value.toLowerCase())
    );
    setClienteSuggestions(filtered);
  };
  const selectCliente = (cliente) => {
    setClienteInput(cliente.nombre + ' (' + cliente.dni + ')');
    setData('dni_cliente', cliente.dni);
    setClienteSuggestions([]);
  };

  // Autocompletado de película
  const handlePeliculaInput = (e) => {
    const value = e.target.value;
    setPeliculaInput(value);
    setData('id_pelicula', '');
    if (value.length === 0) {
      setPeliculaSuggestions([]);
      return;
    }
    const filtered = peliculas.filter(p =>
      p.titulo.toLowerCase().includes(value.toLowerCase())
    );
    setPeliculaSuggestions(filtered);
  };
  const selectPelicula = (pelicula) => {
    setPeliculaInput(pelicula.titulo);
    setData('id_pelicula', pelicula.id_pelicula);
    setPeliculaSuggestions([]);
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
              <input
                type="text"
                className="form-control"
                value={clienteInput}
                onChange={handleClienteInput}
                placeholder="Buscar cliente por nombre o DNI"
                autoComplete="off"
              />
              {clienteSuggestions.length > 0 && (
                <ul className="list-group position-absolute w-100" style={{ zIndex: 10 }}>
                  {clienteSuggestions.map((cliente) => (
                    <li
                      key={cliente.dni}
                      className="list-group-item list-group-item-action"
                      style={{ cursor: 'pointer' }}
                      onClick={() => selectCliente(cliente)}
                    >
                      {cliente.nombre} ({cliente.dni})
                    </li>
                  ))}
                </ul>
              )}
              {errors.dni_cliente && <div className="text-danger">{errors.dni_cliente}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Película</label>
              <input
                type="text"
                className="form-control"
                value={peliculaInput}
                onChange={handlePeliculaInput}
                placeholder="Buscar película por título"
                autoComplete="off"
              />
              {peliculaSuggestions.length > 0 && (
                <ul className="list-group position-absolute w-100" style={{ zIndex: 10 }}>
                  {peliculaSuggestions.map((pelicula) => (
                    <li
                      key={pelicula.id_pelicula}
                      className="list-group-item list-group-item-action"
                      style={{ cursor: 'pointer' }}
                      onClick={() => selectPelicula(pelicula)}
                    >
                      {pelicula.titulo}
                    </li>
                  ))}
                </ul>
              )}
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
            {fechaError && <div className="text-danger">{fechaError}</div>}
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