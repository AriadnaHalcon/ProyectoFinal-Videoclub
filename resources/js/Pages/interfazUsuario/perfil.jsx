import React, { useEffect, useState } from 'react';
import { usePage, useForm, router } from '@inertiajs/react';
import NavbarUsuario from '@/components/NavbarUsuario';
import ModalTarifa from '@/components/ModalTarifa';
import Swal from 'sweetalert2';
import { useCarrito } from '@/components/Carrito';
import AppLayoutUsuario from '@/Layouts/AppLayoutUsuario';

const Perfil = () => {
  const { tarifaActual, tarifas, cliente, success, error: errorTarifa } = usePage().props;
  const [mensaje, setMensaje] = useState(null);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);
  const [loading, setLoading] = useState(false);

  // Formulario para el perfil
  const [form, setForm] = useState({
    dni: cliente?.dni || '',
    nombre: cliente?.nombre || '',
    direccion: cliente?.direccion || '',
    telefono: cliente?.telefono || '',
    email: cliente?.email || '',
  });

  // Formulario para la tarifa
  const { data, setData, processing, errors } = useForm({
    id_tarifa: tarifaActual ? tarifaActual.id_tarifa : '',
  });

  const handleOpenTarifaModal = () => {
    let idTarifa = '';
    if (tarifaActual && tarifaActual.id_tarifa) {
      idTarifa = tarifaActual.id_tarifa;
    } else if (Array.isArray(tarifas) && tarifas.length > 0) {
      idTarifa = tarifas[0].id_tarifa;
    }
    setData('id_tarifa', idTarifa);
  };

  // Cambio de tarifa
  const handleTarifaSubmit = e => {
    e.preventDefault();
    Swal.fire({
      title: 'Guardando...',
      text: 'Por favor, espera mientras se guarda la tarifa.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    router.post(route('cliente.cambiarTarifa'), data, {
      preserveScroll: true,
      onFinish: () => {
        Swal.close();
      },
    });
  };

  useEffect(() => {
    if (success) {
      Swal.fire({
        title: '¡Guardado!',
        text: success,
        icon: 'success',
        timer: 1800,
        showConfirmButton: false,
      }).then(() => {
        window.location.reload();
      });
    }
    if (errorTarifa) {
      Swal.fire({
        title: 'Error',
        text: errorTarifa,
        icon: 'error',
        confirmButtonText: 'Cerrar',
      });
    }
  }, [success, errorTarifa]);

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = e => {
    e.preventDefault();
    setSaving(true);
    setMensaje(null);
    setError(null);

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

    window.axios.post('/perfil', form)
      .then(res => {
        setMensaje(res.data.mensaje);
        Swal.close();
      })
      .catch(err => {
        if (err.response && err.response.data.errors) {
          const errores = Object.values(err.response.data.errors).flat().join(' ');
          setError(errores);
        } else {
          setError('Error al actualizar el perfil.');
        }
        Swal.close();
      })
      .finally(() => setSaving(false));
  };


  // Si no hay datos de perfil, mostrar cargando perfil
  if (!form.dni && !form.nombre && !form.email && !form.direccion && !form.telefono) {
    return <p>Cargando perfil...</p>;
  }

  return (
    <div>
      <div className="container-welcome max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
        <link href="/css/style.css" rel="stylesheet" />
        <h2 className="text-2xl font-bold mb-4 text-center" style={{ color: '#5A3F50' }}>Tu Perfil</h2>
        {mensaje && <p className="mb-4 font-semibold" style={{ color: '#5A3F50' }}>{mensaje}</p>}
        {error && <p className="mb-4 font-semibold" style={{ color: '#D9534F' }}>{error}</p>}
        <form onSubmit={handleSubmit} className="flex flex-col items-center w-full">
        <label className="block mb-2 font-semibold text-left w-full" style={{ color: '#5A3F50' }}>DNI</label>
        <input
          type="text"
          name="dni"
          value={form.dni}
          onChange={handleChange}
          className="form-input"
          required
        />

        <label className="block mb-2 font-semibold text-left w-full" style={{ color: '#5A3F50' }}>Nombre</label>
        <input
          type="text"
          name="nombre"
          value={form.nombre}
          onChange={handleChange}
          className="form-input"
          required
        />

        <label className="block mb-2 font-semibold text-left w-full" style={{ color: '#5A3F50' }}>Dirección</label>
        <input
          type="text"
          name="direccion"
          value={form.direccion}
          onChange={handleChange}
          className="form-input"
        />

        <label className="block mb-2 font-semibold text-left w-full" style={{ color: '#5A3F50' }}>Teléfono</label>
        <input
          type="text"
          name="telefono"
          value={form.telefono}
          onChange={handleChange}
          className="form-input"
        />

        <label className="block mb-2 font-semibold text-left w-full" style={{ color: '#5A3F50' }}>Email</label>
        <input
          type="email"
          name="email"
          value={form.email}
          onChange={handleChange}
          className="form-input"
        />

        <button
          type="submit"
          disabled={saving}
          className="btn-guardar mt-4 mx-auto"
          style={{ display: 'block' }}
        >
          {saving ? 'Guardando...' : 'Guardar cambios'}
        </button>
        
        </form>
      </div>
      
    </div>
  );
};

Perfil.layout = (page) => <AppLayoutUsuario>{page}</AppLayoutUsuario>;
export default Perfil;
