import React, { useRef } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalAgregarCategoria() {
  const modalRef = useRef(null);

  const { data, setData, post, reset, processing, errors } = useForm({
    nombre: '',
    descripcion: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();

    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guarda la categoría.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    post(route('categorias.store'), {
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'La categoría se ha guardado correctamente.',
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
          text: 'Hubo un problema al guardar la categoría.',
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
            <h5 className="modal-title" id="addModalLabel">Agregar Categoría</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            <div className="mb-3">
              <label className="form-label">Nombre</label>
              <input
                type="text"
                className="form-control"
                value={data.nombre}
                onChange={(e) => setData('nombre', e.target.value)}
              />
              {errors.nombre && <div className="text-danger">{errors.nombre}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Descripción</label>
              <textarea
                className="form-control"
                value={data.descripcion}
                onChange={(e) => setData('descripcion', e.target.value)}
              />
              {errors.descripcion && <div className="text-danger">{errors.descripcion}</div>}
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