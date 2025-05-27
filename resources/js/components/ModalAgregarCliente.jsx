import React, { useRef, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalAgregarCliente({ tarifas }) {
  const modalRef = useRef();

  const { data, setData, post, reset, processing, errors } = useForm({
    dni: '',
    nombre: '',
    direccion: '',
    telefono: '',
    email: '',
    id_tarifa: tarifas.length ? tarifas[0].id_tarifa : '',
  });

  // Siempre que el modal se cierre, mueve el foco al botón de abrir modal
  useEffect(() => {
    const modalEl = modalRef.current;
    if (!modalEl) return;

    function handleHidden() {
      const opener = document.getElementById('abrirModalAgregarCliente');
      if (opener) opener.focus();
    }

    modalEl.addEventListener('hidden.bs.modal', handleHidden);
    return () => {
      modalEl.removeEventListener('hidden.bs.modal', handleHidden);
    };
  }, []);

  // Espera a que el modal termine de cerrarse antes de continuar (para submit)
  const closeModalAndThen = (callback) => {
    const modal = window.bootstrap.Modal.getInstance(modalRef.current);
    if (modal) {
      modalRef.current.addEventListener('hidden.bs.modal', function handler() {
        modalRef.current.removeEventListener('hidden.bs.modal', handler);

        // Limpieza manual igual que en ModalAgregarTarifa.jsx
        setTimeout(() => {
          document.querySelectorAll('.modal-backdrop').forEach((b) => b.remove());
          document.body.classList.remove('modal-open');
          document.body.style.overflow = '';
          // Mueve el foco al botón de abrir modal
          const opener = document.getElementById('abrirModalAgregarCliente');
          if (opener) opener.focus();
          callback();
        }, 300); // Espera la animación
      });
      modal.hide();
    } else {
      callback();
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guarda el cliente.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    post(route('clientes.store'), {
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'El cliente se ha guardado correctamente.',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false,
        });

        // Espera a que el modal se cierre antes de resetear el formulario
        closeModalAndThen(() => {
          reset();
        });
      },
      onError: () => {
        Swal.fire({
          title: 'Error',
          text: 'Hubo un problema al guardar el cliente.',
          icon: 'error',
          confirmButtonText: 'Cerrar',
        });
      },
    });
  };

  return (
    <div
      className="modal fade"
      id="addModal"
      tabIndex="-1"
      aria-labelledby="addModalLabel"
      aria-hidden="true"
      ref={modalRef}
    >
      <div className="modal-dialog">
        <form onSubmit={handleSubmit} className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="addModalLabel">Agregar Cliente</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            {[['DNI', 'dni'], ['Nombre', 'nombre'], ['Dirección', 'direccion'], ['Teléfono', 'telefono'], ['Email', 'email']].map(([label, field]) => (
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