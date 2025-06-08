import React, { useEffect, useRef } from 'react';
import { Link, usePage } from '@inertiajs/react';



export default function Navbar() {  useEffect(() => {
    const initNavbar = () => {
      const collapseEl = document.getElementById('navbarAdminResponsive');
      if (collapseEl && window.bootstrap) {
        const bsCollapse = new window.bootstrap.Collapse(collapseEl, {
          toggle: false
        });
        return bsCollapse;
      }
      return null;
    };

    // Intentar inicializar el navbar al cargar el componente
    let collapse = initNavbar();
    
    if (!collapse) {
      const timer = setTimeout(() => {
        collapse = initNavbar();
      }, 100);
      return () => clearTimeout(timer);
    }
  }, []);
  const { auth } = usePage().props;
  const dropdownToggleRef = useRef(null);
  const dropdownInstanceRef = useRef(null);

  useEffect(() => {
    if (dropdownToggleRef.current && window.bootstrap) {
      dropdownInstanceRef.current = new window.bootstrap.Dropdown(dropdownToggleRef.current);
    }
    return () => {
      if (dropdownInstanceRef.current) {
        dropdownInstanceRef.current.dispose();
      }
    };
  }, []);

  const handleDropdownClick = (e) => {
    e.preventDefault();
    if (dropdownInstanceRef.current) {
      dropdownInstanceRef.current.toggle();
    }
  };

  const [expanded, setExpanded] = React.useState(false);

  useEffect(() => {
    const collapseEl = document.getElementById('navbarAdminResponsive');
    if (!collapseEl || !window.bootstrap) return;
    const handler = () => {
      setExpanded(collapseEl.classList.contains('show'));
    };
    collapseEl.addEventListener('shown.bs.collapse', handler);
    collapseEl.addEventListener('hidden.bs.collapse', handler);
    setExpanded(collapseEl.classList.contains('show'));
    return () => {
      collapseEl.removeEventListener('shown.bs.collapse', handler);
      collapseEl.removeEventListener('hidden.bs.collapse', handler);
    };
  }, []);

  const handleHamburgerClick = () => {
    const collapseEl = document.getElementById('navbarAdminResponsive');
    if (!collapseEl || !window.bootstrap) return;
    const collapse = window.bootstrap.Collapse.getOrCreateInstance(collapseEl);
    if (collapseEl.classList.contains('show')) {
      collapse.hide();
      setExpanded(false);
    } else {
      collapse.show();
      setExpanded(true);
    }
  };

  return (    <nav className="navbar navbar-expand-lg navbar-light navbar-custom">
      <div className="container-fluid">
        <Link className="navbar-brand text-pastel-pink" href="/">
          Videoclub
        </Link>
        <style>
          {`
            @media (min-width: 992px) {
              .navbar-expand-lg .navbar-collapse {
                display: flex !important;
              }
              .navbar-expand-lg .navbar-toggler {
                display: none;
              }
            }
          `}
        </style>
        <button
          className="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarAdminResponsive"
          aria-controls="navbarAdminResponsive"
          aria-expanded={expanded}
          aria-label="Toggle navigation"
          onClick={handleHamburgerClick}
        >
          <span className="navbar-toggler-icon"></span>
        </button>        <div className="navbar-collapse" id="navbarAdminResponsive">
          <ul className="navbar-nav ms-auto">
            <li className="nav-item">
              <Link className="nav-link text-pastel-pink" href="/clientes" onClick={() => {
                const collapse = window.bootstrap?.Collapse.getInstance(document.getElementById('navbarAdminResponsive'));
                if (collapse) collapse.hide();
              }}>Clientes</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link text-pastel-pink" href="/peliculas" onClick={() => {
                const collapse = window.bootstrap?.Collapse.getInstance(document.getElementById('navbarAdminResponsive'));
                if (collapse) collapse.hide();
              }}>Películas</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link text-pastel-pink" href="/alquileres" onClick={() => {
                const collapse = window.bootstrap?.Collapse.getInstance(document.getElementById('navbarAdminResponsive'));
                if (collapse) collapse.hide();
              }}>Alquileres</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link text-pastel-pink" href="/categorias" onClick={() => {
                const collapse = window.bootstrap?.Collapse.getInstance(document.getElementById('navbarAdminResponsive'));
                if (collapse) collapse.hide();
              }}>Categorías</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link text-pastel-pink" href="/tarifas" onClick={() => {
                const collapse = window.bootstrap?.Collapse.getInstance(document.getElementById('navbarAdminResponsive'));
                if (collapse) collapse.hide();
              }}>Tarifas</Link>
            </li>
            <li className="nav-item dropdown">
              <a
                href="#"
                className="nav-link dropdown-toggle text-pastel-pink"
                id="navbarDropdown"
                role="button"
                aria-expanded="false"
                ref={dropdownToggleRef}
                onClick={handleDropdownClick}
              >
                {auth.user ? auth.user.name : 'Usuario'}
              </a>
              <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                  <Link className="dropdown-item" href="/logout" method="post" as="button">
                    Cerrar sesión
                  </Link>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
}