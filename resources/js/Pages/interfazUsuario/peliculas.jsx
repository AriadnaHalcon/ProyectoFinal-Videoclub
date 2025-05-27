import React, { useState } from 'react';
import { usePage, useForm } from '@inertiajs/react';
import TarjetaPelicula from '@/components/TarjetaPelicula';
import { useCarrito } from '@/components/Carrito';
import NavbarUsuario from '@/components/NavbarUsuario';
import Swal from 'sweetalert2';
import { useEffect } from 'react';
import { router } from '@inertiajs/react';
import SearchBar from '@/components/SearchBar';
import AppLayoutUsuario from '@/Layouts/AppLayoutUsuario';

const Peliculas = () => {
  const { peliculas, tarifaActual, tarifas, success, error, search: initialSearch } = usePage().props;
  const [search, setSearch] = useState(initialSearch);
  const { addToCarrito } = useCarrito();

  useEffect(() => {
    setSearch(initialSearch);
  }, [initialSearch]);

  // Formulario para la tarifa
  const { data, setData, post, processing, errors } = useForm({
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
      router.visit(route('cliente.peliculas'), {
        replace: true,
        preserveState: true,
        preserveScroll: true,
        only: [], 
      });
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

  const handleAddToCart = (pelicula, tipo) => {
    const added = addToCarrito(pelicula, tipo);
    if (!added) {
      Swal.fire({
        title: 'Ya añadido',
        text: `Ya tienes esta película para ${tipo} en el carrito.`,
        icon: 'info',
        timer: 1500,
        showConfirmButton: false,
      });
      return;
    }
  };

  return (
    <div style={{ background: '#FADADD', minHeight: '100vh', padding: '0' }}>
      <link href="/css/style.css" rel="stylesheet" />
      <h1 className="text-4xl font-bold mb-4 text-center" style={{ color: '#5A3F50', marginTop: '30px'}}> Catálogo de Películas</h1>
      <SearchBar routeName="peliculas.usuario" search={search} />   
    <div className="flex flex-wrap justify-center">
        {peliculas.map(pelicula => (
          <TarjetaPelicula
            key={pelicula.id_pelicula}
            pelicula={pelicula}
            onAddToCart={handleAddToCart}
            tarifaActual={tarifaActual}
          />        
        ))}
    </div>
</div>
  );
};

Peliculas.layout = (page) => <AppLayoutUsuario>{page}</AppLayoutUsuario>;
export default Peliculas;
