import React, { useEffect, useRef, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { useCarrito } from './Carrito';
import Carrito from './Carrito';

const NavbarUsuario = ({ onMiTarifaClick }) => {
  const { auth } = usePage().props;
  const dropdownToggleRef = useRef(null);
  const dropdownInstanceRef = useRef(null);
  const { carrito, removeFromCarrito, clearCarrito } = useCarrito();
  const [showCarritoModal, setShowCarritoModal] = useState(false);

  useEffect(() => {
    if (window.bootstrap && dropdownToggleRef.current) {
      dropdownInstanceRef.current = new window.bootstrap.Dropdown(dropdownToggleRef.current);
    }

    return () => {
      if (dropdownInstanceRef.current) {
        dropdownInstanceRef.current.dispose();
      }
    };
  }, []);

  const toggleDropdown = (e) => {
    e.preventDefault();
    if (dropdownInstanceRef.current) {
      if (dropdownToggleRef.current.getAttribute('aria-expanded') === 'true') {
        dropdownInstanceRef.current.hide();
      } else {
        dropdownInstanceRef.current.show();
      }
    }
  };

  return (
    <>
      <nav
        className="navbar navbar-expand-lg navbar-light px-3 shadow"
        style={{
          background: '#F1C6D1',
          borderRadius: 0,
          marginTop: 0,
          position: 'sticky',
          top: 0,
          zIndex: 50,
          minHeight: 60,
        }}
      >
        <div className="container-fluid">
          <span
            className="navbar-brand fw-bold"
            style={{ color: '#5A3F50', letterSpacing: 1 }}
          >
            🎬 Videoclub
          </span>
          <ul className="navbar-nav ms-auto" style={{ alignItems: 'center' }}>
            <li className="nav-item me-3">
              <Link
                className="nav-link"
                href="/peliculas-usuario"
                style={{ color: '#5A3F50', fontWeight: 'bold' }}
              >
                Películas
              </Link>
            </li>
            <li className="nav-item me-3">
              <Link
                className="nav-link"
                href="/perfil"
                style={{ color: '#5A3F50', fontWeight: 'bold' }}
              >
                Perfil
              </Link>
            </li>
            <li className="nav-item me-3">
              <Link
                className="nav-link"
                href={route('cliente.misAlquileres')}
                style={{ color: '#5A3F50', fontWeight: 'bold' }}
              >
                Mis alquileres
              </Link>
            </li>
            <li className="nav-item me-3">
              <Link
                className="nav-link"
                href="#"
                onClick={e => {
                  e.preventDefault();
                  onMiTarifaClick();
                }}
                style={{ color: '#5A3F50', fontWeight: 'bold', marginLeft: 8 }}
              >
                Mi Tarifa
              </Link>
            </li>
            <li className="nav-item dropdown">
              <button
                ref={dropdownToggleRef}
                className="nav-link dropdown-toggle btn btn-link"
                type="button"
                id="navbarDropdownUser"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style={{
                  color: '#5A3F50',
                  fontWeight: 'bold',
                  textDecoration: 'none',
                  fontSize: 16,
                  padding: '6px 12px',
                  borderRadius: 8,
                  transition: 'background 0.2s',
                }}
                onClick={toggleDropdown}
              >
                {auth.user ? auth.user.name : 'Usuario'}
              </button>
              <ul
                className="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdownUser"
              >
                <li>
                  <Link className="dropdown-item" href="/logout" method="post" as="button">
                    Cerrar sesión
                  </Link>
                </li>
              </ul>
            </li>
            <li className="nav-item me-3">
              <button
                className="btn btn-link position-relative"
                style={{ color: '#5A3F50', fontWeight: 'bold', fontSize: 20 }}
                onClick={() => setShowCarritoModal(true)}
                title="Ver carrito"
              >
                🛒
                {carrito.length > 0 && (
                  <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style={{ fontSize: 12 }}>
                    {carrito.length}
                  </span>
                )}
              </button>
            </li>
          </ul>
        </div>
      </nav>

      {/* Modal Carrito */}
      <div
        className={`modal fade${showCarritoModal ? ' show d-block' : ''}`}
        tabIndex="-1"
        style={{ background: showCarritoModal ? 'rgba(0,0,0,0.3)' : 'transparent' }}
        aria-modal={showCarritoModal ? 'true' : undefined}
        role="dialog"
      >
        <div className="modal-dialog">
          <div className="modal-content" style={{ backgroundColor: '#FADADD', borderRadius: 10, padding: 20, border: 'none' }}>
            <div className="modal-header" style={{ backgroundColor: '#F1C6D1', color: '#5A3F50', borderBottom: '2px solid #D28B7A', borderTopLeftRadius: 10, borderTopRightRadius: 10 }}>
              <h5 className="modal-title" style={{ fontWeight: 'bold' }}>Carrito</h5>
              <button type="button" className="btn-close" onClick={() => setShowCarritoModal(false)}></button>
            </div>
            <div className="modal-body">
              <Carrito carrito={carrito} onRemove={removeFromCarrito} onClear={clearCarrito} tarifaActual={usePage().props.tarifaActual} />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default NavbarUsuario;
