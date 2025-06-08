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
  const [fechaError, setFechaError] = useState('');
  const [clienteInput, setClienteInput] = useState('');
  const [clienteSuggestions, setClienteSuggestions] = useState([]);
  const [peliculaInput, setPeliculaInput] = useState('');
  const [peliculaSuggestions, setPeliculaSuggestions] = useState([]);

  useEffect(() => {
    const handler = (e) => {
      const alquiler = e.detail;
      setId(alquiler.id_alquiler);
    
      const parseFecha = (fecha) => {
        if (!fecha) return '';
        const partes = fecha.split('/');
        if (partes.length === 3) {
          return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        }
        return fecha;
      };
      setData({
        dni_cliente: alquiler.cliente?.dni || '',
        id_pelicula: alquiler.pelicula?.id_pelicula || '',
        fecha_alquiler: parseFecha(alquiler.fecha_alquiler) || '',
        fecha_devolucion: parseFecha(alquiler.fecha_devolucion) || '',
        estado: alquiler.estado || 'Pendiente',
      });
      setClienteInput(alquiler.cliente ? `${alquiler.cliente.nombre} (${alquiler.cliente.dni})` : '');
      setPeliculaInput(alquiler.pelicula ? alquiler.pelicula.titulo : '');
      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    };
    window.addEventListener('abrir-modal-editar-alquiler', handler);
    return () => window.removeEventListener('abrir-modal-editar-alquiler', handler);
  }, []);

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

  const handleSubmit = (e) => {
    e.preventDefault();
    setFechaError('');
    if (data.fecha_alquiler && data.fecha_devolucion && data.fecha_alquiler > data.fecha_devolucion) {
      setFechaError('La fecha de devolución debe ser igual o posterior a la fecha de alquiler.');
      return;
    }
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