import React, { useState } from 'react';
import NavbarUsuario from '@/components/NavbarUsuario';
import { Helmet, HelmetProvider } from 'react-helmet-async';
import { CarritoProvider } from '@/components/Carrito';
import ModalTarifa from '@/components/ModalTarifa';
import { usePage, useForm } from '@inertiajs/react';
import Swal from 'sweetalert2';
import Footer from '@/components/Footer';

export default function AppLayoutUsuario({ children }) {
  const { tarifaActual, tarifas, success, error } = usePage().props;
  console.log('LAYOUT PROPS:', { tarifaActual, tarifas });
  const [showTarifaModal, setShowTarifaModal] = useState(false);
  const { data, setData, post, processing, errors } = useForm({
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
    setShowTarifaModal(true);
  };

  const handleCloseTarifaModal = () => setShowTarifaModal(false);

  function handleTarifaSubmit(e) {
    if (e && e.preventDefault) e.preventDefault();
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
    post(route('cliente.cambiarTarifa'), {
      preserveScroll: true,
      onSuccess: () => {
        Swal.fire({
          title: '¡Guardado!',
          text: 'Tarifa actualizada correctamente.',
          icon: 'success',
          timer: 1800,
          showConfirmButton: false,
        }).then(() => {
          setShowTarifaModal(false);
          window.location.reload();
        });
      },
      onError: () => {
        Swal.fire({
          title: 'Error',
          text: 'No se pudo actualizar la tarifa.',
          icon: 'error',
          confirmButtonText: 'Cerrar',
        });
      },
      onFinish: () => {
        Swal.close();
      },
    });
  };

  return (
    <HelmetProvider>
      <Helmet>
        <link
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap"
          rel="stylesheet"
        />
        <link
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
          rel="stylesheet"
        />
        <link href="/css/style.css" rel="stylesheet" />
      </Helmet>
      <CarritoProvider>
        <div
          style={{
            minHeight: '100vh',
            backgroundColor: '#FADADD',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          <NavbarUsuario onMiTarifaClick={handleOpenTarifaModal} />
          <main className="container py-4" style={{ flex: 1 }}>
            {children}
          </main>
          <Footer />
          {/* ModalTarifa global */}
          {showTarifaModal && (
            <div
              className="modal fade show d-block"
              tabIndex="-1"
              style={{ background: 'rgba(0,0,0,0.3)' }}
              aria-modal="true"
              role="dialog"
            >
              <div className="modal-dialog">
                <ModalTarifa
                  tarifaActual={tarifaActual}
                  tarifas={tarifas}
                  onSubmit={handleTarifaSubmit}
                  data={data}
                  setData={setData}
                  processing={processing}
                  errors={errors}
                  success={success}
                  error={error}
                  onClose={handleCloseTarifaModal}
                />
              </div>
            </div>
          )}
        </div>
      </CarritoProvider>
    </HelmetProvider>
  );
}
