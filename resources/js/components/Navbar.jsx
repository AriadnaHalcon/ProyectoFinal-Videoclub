import React, { useEffect, useRef } from 'react';
import { Link, usePage } from '@inertiajs/react';



export default function Navbar() {
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

  return (
    <nav className="navbar navbar-expand-lg navbar-light navbar-custom">
      <div className="container-fluid">
        <Link className="navbar-brand text-pastel-pink" href="/">
          Videoclub
        </Link>
        <ul className="navbar-nav ms-auto">
          <li className="nav-item">
            <Link className="nav-link text-pastel-pink" href="/clientes">Clientes</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link text-pastel-pink" href="/peliculas">Películas</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link text-pastel-pink" href="/alquileres">Alquileres</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link text-pastel-pink" href="/categorias">Categorías</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link text-pastel-pink" href="/tarifas">Tarifas</Link>
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
    </nav>
  );
}