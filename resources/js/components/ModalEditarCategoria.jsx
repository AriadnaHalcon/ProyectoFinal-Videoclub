import React, { useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalEditarCategoria() {
  const { data, setData, put, reset, processing, errors } = useForm({
    nombre: '',
    descripcion: '',
  });

  const [id, setId] = useState(null);

  useEffect(() => {
    const handler = (e) => {
      const categoria = e.detail;
      setId(categoria.id_categoria);
      setData({
        nombre: categoria.nombre || '',
        descripcion: categoria.descripcion || '',
      });

      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    };

    window.addEventListener('abrir-modal-editar-categoria', handler);
    return () => window.removeEventListener('abrir-modal-editar-categoria', handler);
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

    put(route('categorias.update', id), {
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
            <h5 className="modal-title" id="editModalLabel">Editar Categoría</h5>
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