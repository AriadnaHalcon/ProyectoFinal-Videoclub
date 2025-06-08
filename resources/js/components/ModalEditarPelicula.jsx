import React, { useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalEditarPelicula({ categorias }) {
  const { data, setData, put, reset, processing, errors } = useForm({
    titulo: '',
    id_categoria: '',
    director: '',
    anio_estreno: '',
    stock: '',
    precio: '',
    imagen: null,
  });

  const [id, setId] = useState(null);

  useEffect(() => {
    const handler = (e) => {
      const pelicula = e.detail;
      setId(pelicula.id_pelicula);
      setData({
        titulo: pelicula.titulo || '',
        id_categoria: pelicula.id_categoria || '',
        director: pelicula.director || '',
        anio_estreno: pelicula.anio_estreno || '',
        stock: pelicula.stock || '',
        precio: pelicula.precio || '',
        imagen: null,
      });

      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    };

    window.addEventListener('abrir-modal-editar-pelicula', handler);
    return () => window.removeEventListener('abrir-modal-editar-pelicula', handler);
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

    put(route('peliculas.update', id), {
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
            <h5 className="modal-title" id="editModalLabel">Editar Película</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div className="modal-body">
            {[['Título', 'titulo'], ['Director', 'director'], ['Año de Estreno', 'anio_estreno'], ['Stock', 'stock'], ['Precio', 'precio']].map(([label, field]) => (
              <div className="mb-3" key={field}>
                <label className="form-label">{label}</label>
                <input
                  type={field === 'precio' ? 'number' : field === 'anio_estreno' ? 'number' : field === 'stock' ? 'number' : 'text'}
                  className="form-control"
                  value={data[field]}
                  onChange={(e) => setData(field, e.target.value)}
                  {...(field === 'anio_estreno' ? { min: 1900, max: new Date().getFullYear() } : {})}
                  {...(field === 'stock' ? { min: 0 } : {})}
                  {...(field === 'precio' ? { min: 0, step: '0.01' } : {})}
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
                accept="image/*"
                onChange={(e) => setData('imagen', e.target.files[0])}
              />
              {errors.imagen && <div className="text-danger">{errors.imagen}</div>}
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