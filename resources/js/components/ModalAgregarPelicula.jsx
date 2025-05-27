import React, { useRef, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalAgregarPelicula({ categorias }) {
  const modalRef = useRef(null);

  const { data, setData, post, reset, processing, errors } = useForm({
    titulo: '',
    id_categoria: categorias.length ? categorias[0].id_categoria : '',
    director: '',
    anio_estreno: '',
    stock: '',
    precio: '',
    imagen: null,
  });

  const handleSubmit = (e) => {
    e.preventDefault();

    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guarda la película.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    post(route('peliculas.store'), {
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'La película se ha guardado correctamente.',
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
          text: 'Hubo un problema al guardar la película.',
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
      id="addModalPelicula"
      tabIndex="-1"
      aria-labelledby="addModalPeliculaLabel"
      aria-hidden="true"
    >
      <div className="modal-dialog">
        <form onSubmit={handleSubmit} className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="addModalPeliculaLabel">Agregar Película</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            {[['Título', 'titulo'], ['Director', 'director'], ['Año de Estreno', 'anio_estreno'], ['Stock', 'stock'], ['Precio', 'precio']].map(([label, field]) => (
              <div className="mb-3" key={field}>
                <label className="form-label">{label}</label>
                <input
                  type={field === 'precio' ? 'number' : 'text'}
                  className="form-control"
                  value={data[field]}
                  onChange={(e) => setData(field, e.target.value)}
                />
                {errors[field] && <div className="text-danger">{errors[field]}</div>}
              </div>
            ))}
            <div className="mb-3">
              <label className="form-label">Categoría</label>
              <select
                className="form-control"
                value={data.id_categoria}
                onChange={(e) => setData('id_categoria', e.target.value)}
              >
                {categorias.map((categoria) => (
                  <option key={categoria.id_categoria} value={categoria.id_categoria}>
                    {categoria.nombre}
                  </option>
                ))}
              </select>
              {errors.id_categoria && <div className="text-danger">{errors.id_categoria}</div>}
            </div>
            <div className="mb-3">
              <label className="form-label">Imagen</label>
              <input
                type="file"
                className="form-control"
                onChange={(e) => setData('imagen', e.target.files[0])}
              />
              {errors.imagen && <div className="text-danger">{errors.imagen}</div>}
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