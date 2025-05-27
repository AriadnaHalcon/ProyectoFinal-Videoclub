import React, { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

export default function SearchBar({ routeName, search }) {
  const [searchTerm, setSearchTerm] = useState(search || '');

  useEffect(() => {
    setSearchTerm(search || '');
  }, [search]);

  const handleSubmit = (e) => {
    e.preventDefault();
    router.get(
      route(routeName),
      { search: searchTerm },
      {
        preserveState: true,
        replace: true,
      }
    );
  };

  const handleClear = () => {
    setSearchTerm('');
    router.get(
      route(routeName),
      {},
      {
        preserveState: true,
        replace: true,
      }
    );
  };

  return (
    <div className="d-flex justify-content-center mb-4">
      <form onSubmit={handleSubmit} style={{ maxWidth: '1100px', width: '100%' }}>
        <div className="input-group">
          <input
            type="text"
            name="search"
            className="form-control search-input"
            placeholder="Buscar por filtro..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
          <button
            type="submit"
            className="btn btn-primary"
            style={{ backgroundColor: 'rgb(235, 138, 162)', borderColor: '#fc6e6e' }}
          >
            Buscar
          </button>
          <button
            type="button"
            className="btn btn-secondary ms-2"
            onClick={handleClear}
          >
            Limpiar
          </button>
        </div>
      </form>
    </div>
  );
}