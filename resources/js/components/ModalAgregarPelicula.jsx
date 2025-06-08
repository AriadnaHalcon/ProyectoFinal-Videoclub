import React, { useRef, useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function ModalAgregarPelicula({ categorias }) {
  const modalRef = useRef(null);
  const [categoriasState, setCategoriasState] = useState(categorias);
  const [showCategoriaModal, setShowCategoriaModal] = useState(false);
  const [nuevaCategoria, setNuevaCategoria] = useState('');
  const [nuevaDescripcion, setNuevaDescripcion] = useState('');
  const [categoriaError, setCategoriaError] = useState('');
  const [categoriaValidacion, setCategoriaValidacion] = useState({});

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

        // limpiar el modal y eliminar el backdrop
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

  const handleAgregarCategoria = async (e) => {
    e.preventDefault();
    setCategoriaError('');
    if (!nuevaCategoria.trim()) {
      setCategoriaError('El nombre es obligatorio.');
      return;
    }
    try {
      const response = await window.axios.post('/categorias', { nombre: nuevaCategoria, descripcion: nuevaDescripcion });
      setCategoriaValidacion({});
      if (response.data && response.data.id_categoria) {
        const nueva = { id_categoria: response.data.id_categoria, nombre: nuevaCategoria };
        setCategoriasState([...categoriasState, nueva]);
        setData('id_categoria', response.data.id_categoria);
        setShowCategoriaModal(false);
        setNuevaCategoria('');
        setNuevaDescripcion('');
      } else {
        setCategoriaError('No se pudo crear la categoría.');
      }
    } catch (err) {
      if (err.response && err.response.data && err.response.data.errors) {
        setCategoriaValidacion(err.response.data.errors);
      } else {
        setCategoriaError('Error al crear la categoría.');
      }
    }
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
              <div className="d-flex align-items-center">
                <select
                  className="form-control"
                  value={data.id_categoria}
                  onChange={(e) => setData('id_categoria', e.target.value)}
                >
                  {categoriasState.map((categoria) => (
                    <option key={categoria.id_categoria} value={categoria.id_categoria}>
                      {categoria.nombre}
                    </option>
                  ))}
                </select>
                <button
                  type="button"
                  className="btn btn-link ms-2"
                  onClick={() => setShowCategoriaModal(true)}
                  style={{ fontSize: '1.2em' }}
                >
                  + Agregar categoría
                </button>
              </div>
              {errors.id_categoria && <div className="text-danger">{errors.id_categoria}</div>}
            </div>
            {showCategoriaModal && (
              <div style={{ position: 'fixed', top: 0, left: 0, width: '100vw', height: '100vh', background: 'rgba(0,0,0,0.3)', zIndex: 2000, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <div className="modal-dialog" style={{ zIndex: 2001, minWidth: 350 }}>
                  <div className="modal-content">
                    <div className="modal-header">
                      <h5 className="modal-title">Agregar Categoría</h5>
                      <button type="button" className="btn-close" onClick={() => setShowCategoriaModal(false)}></button>
                    </div>
                    <div className="modal-body">
                      <input
                        type="text"
                        className="form-control mb-2"
                        value={nuevaCategoria}
                        onChange={e => setNuevaCategoria(e.target.value)}
                        placeholder="Nombre de la categoría"
                        required
                      />
                      <textarea
                        className="form-control"
                        value={nuevaDescripcion}
                        onChange={e => setNuevaDescripcion(e.target.value)}
                        placeholder="Descripción (opcional)"
                        rows={2}
                      />
                      {categoriaError && <div className="text-danger mt-2">{categoriaError}</div>}
                      {categoriaValidacion.nombre && <div className="text-danger mt-2">{categoriaValidacion.nombre[0]}</div>}
                      {categoriaValidacion.descripcion && <div className="text-danger mt-2">{categoriaValidacion.descripcion[0]}</div>}
                    </div>
                    <div className="modal-footer">
                      <button type="button" className="btn btn-primary" onClick={handleAgregarCategoria}>Guardar</button>
                      <button type="button" className="btn btn-secondary" onClick={() => setShowCategoriaModal(false)}>Cancelar</button>
                    </div>
                  </div>
                </div>
              </div>
            )}
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