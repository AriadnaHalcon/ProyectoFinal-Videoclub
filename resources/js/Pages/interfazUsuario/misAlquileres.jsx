import React, { useState } from 'react';
import { usePage, useForm } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import NavbarUsuario from '@/components/NavbarUsuario';
import ModalTarifa from '@/components/ModalTarifa';
import { useCarrito } from '@/components/Carrito';
import AppLayoutUsuario from '@/Layouts/AppLayoutUsuario';
import Swal from 'sweetalert2';

function calcularDiasRestantes(fechaDevolucion) {
  const hoy = new Date();
  const devolucion = new Date(fechaDevolucion);
  const diff = devolucion - hoy;
  const dias = Math.ceil(diff / (1000 * 60 * 60 * 24));
  return dias;
}

const MisAlquileres = () => {
  const { alquileres, tarifaActual, tarifas, success, error } = usePage().props;
  console.log('MIS ALQUILERES PROPS:', { tarifaActual, tarifas });
  const [showTarifaModal, setShowTarifaModal] = useState(false);
  const { data, setData, processing, errors } = useForm({
    id_tarifa: tarifaActual ? tarifaActual.id_tarifa : '',
  });

  React.useEffect(() => {
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
    if (error) {
      Swal.fire({
        title: 'Error',
        text: error,
        icon: 'error',
        confirmButtonText: 'Cerrar',
      });
    }
  }, [success, error]);

  const handleOpenTarifaModal = () => {
    let idTarifa = '';
    if (tarifaActual && tarifaActual.id_tarifa) {
      idTarifa = tarifaActual.id_tarifa;
    } else if (Array.isArray(tarifas) && tarifas.length > 0) {
      idTarifa = tarifas[0].id_tarifa;
    }
    setData('id_tarifa', idTarifa);
    // Ya no se usa window.bootstrap.Modal, el layout gestiona el modal
  };

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

  return (
    <div>
    <link href="/css/style.css" rel="stylesheet" />
      <div className="container py-5">
        <h1 className="text-3xl font-bold mb-4 text-center" style={{ color: '#5A3F50' }}>
          Mis Alquileres
        </h1>
        {alquileres.length === 0 ? (
          <p className="text-center" style={{ color: '#D28B7A' }}>
            No tienes alquileres activos.
          </p>
        ) : (
          <div className="table-responsive">
            <table className="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Película</th>
                  <th>Fecha de alquiler</th>
                  <th>Fecha de devolución</th>
                  <th>Estado</th>
                  <th>Precio</th>
                  <th>Tiempo restante</th>
                </tr>
              </thead>
              <tbody>
                {alquileres.map(alquiler => (
                  <tr key={alquiler.id_alquiler}>
                    <td>{alquiler.pelicula?.titulo}</td>
                    <td>{alquiler.fecha_alquiler?.slice(0, 10)}</td>
                    <td>{alquiler.fecha_devolucion?.slice(0, 10)}</td>
                    <td>{alquiler.estado}</td>
                    <td>€{Number(alquiler.precio_rebajado).toFixed(2)}</td>
                    <td>
                      {alquiler.estado === 'Pendiente'
                        ? `${calcularDiasRestantes(alquiler.fecha_devolucion)} días`
                        : 'Finalizado'}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
      
    </div>
  );
};

MisAlquileres.layout = (page) => <AppLayoutUsuario>{page}</AppLayoutUsuario>;
export default MisAlquileres;
