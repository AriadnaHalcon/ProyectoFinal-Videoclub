import React, { useEffect, useState } from 'react';
import { usePage, useForm, router } from '@inertiajs/react';
import Swal from 'sweetalert2';
import AppLayoutUsuario from '@/Layouts/AppLayoutUsuario';

const Perfil = () => {
  const { tarifaActual, tarifas, cliente, success, error: errorTarifa } = usePage().props;
  const [mensaje, setMensaje] = useState(null);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);
  const [dniValido, setDniValido] = useState(true);
  const [telefonoValido, setTelefonoValido] = useState(true);

  // Formulario para el perfil
  const [form, setForm] = useState({
    dni: cliente?.dni || '',
    nombre: cliente?.nombre || '',
    direccion: cliente?.direccion || '',
    telefono: cliente?.telefono || '',
    email: cliente?.email || '',
  });

  const { data, setData, processing, errors } = useForm({
    id_tarifa: tarifaActual ? tarifaActual.id_tarifa : '',
  });

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
    const { name, value } = e.target;
    let newValue = value;
    if (name === 'dni') {
      newValue = newValue.toUpperCase().replace(/[^0-9A-Za-z]/g, '');
      if (newValue.length > 9) newValue = newValue.slice(0, 9);
      setDniValido(/^[0-9]{8}[A-Za-z]$/.test(newValue));
    }
    if (name === 'telefono') {
      newValue = newValue.replace(/[^0-9]/g, '');
      if (newValue.length > 9) newValue = newValue.slice(0, 9);
      setTelefonoValido(/^[0-9]{9}$/.test(newValue));
    }
    setForm({ ...form, [name]: newValue });
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

  // Si no hay datos de perfil, muestra "cargando perfil"
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
          maxLength={9}
        />
        {!dniValido && form.dni.length === 9 && (
          <div style={{ color: 'red', marginTop: 4 }}>
            El DNI debe tener 8 números seguidos de una letra (ejemplo: 12345678A).
          </div>
        )}

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
          maxLength={9}
        />
        {!telefonoValido && form.telefono.length === 9 && (
          <div style={{ color: 'red', marginTop: 4 }}>
            El teléfono debe tener exactamente 9 dígitos numéricos.
          </div>
        )}

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
          disabled={saving || !dniValido || !telefonoValido}
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
