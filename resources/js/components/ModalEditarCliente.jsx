import React, { useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalEditarCliente({ tarifas }) {
  const { data, setData, put, reset, processing, errors } = useForm({
    nombre: '',
    direccion: '',
    telefono: '',
    email: '',
    id_tarifa: '',
  });

  const [dni, setDni] = useState(null);

  useEffect(() => {
    const handler = (e) => {
      const cliente = e.detail;
      setDni(cliente.dni);
      setData({
        nombre: cliente.nombre || '',
        direccion: cliente.direccion || '',
        telefono: cliente.telefono || '',
        email: cliente.email || '',
        id_tarifa: cliente.id_tarifa || '',
      });

      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    };

    window.addEventListener('abrir-modal-editar', handler);
    return () => window.removeEventListener('abrir-modal-editar', handler);
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!dni) {
      console.error('DNI no definido');
      return;
    }
  
    console.log('Enviando datos:', data);
  
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
  
    put(route('clientes.update', dni), {
      onSuccess: () => {
        console.log('Cambios guardados con éxito'); 
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
      onError: (error) => {
        console.error('Error al guardar los cambios:', error); 
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
            <h5 className="modal-title" id="editModalLabel">Editar Cliente</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            {[['Nombre', 'nombre'], ['Dirección', 'direccion'], ['Teléfono', 'telefono'], ['Email', 'email']].map(([label, field]) => (
              <div className="mb-3" key={field}>
                <label className="form-label">{label}</label>
                <input
                  type={field === 'email' ? 'email' : 'text'}
                  className="form-control"
                  value={data[field]}
                  onChange={(e) => setData(field, e.target.value)}
                />
                {errors[field] && <div className="text-danger">{errors[field]}</div>}
              </div>
            ))}
            <div className="mb-3">
              <label className="form-label">Tarifa</label>
              <select
                className="form-control"
                value={data.id_tarifa}
                onChange={(e) => setData('id_tarifa', e.target.value)}
              >
                {tarifas.map((tarifa) => (
                  <option key={tarifa.id_tarifa} value={tarifa.id_tarifa}>
                    {tarifa.nombre}
                  </option>
                ))}
              </select>
              {errors.id_tarifa && <div className="text-danger">{errors.id_tarifa}</div>}
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