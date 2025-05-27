import React from 'react';

const TarjetaPelicula = ({ pelicula, onAddToCart, tarifaActual }) => {
  // Calcula el precio rebajado para alquiler si hay tarifa
  const descuento = tarifaActual?.descuento || 0;
  const precioAlquiler = (Number(pelicula.precio) * (1 - descuento / 100)).toFixed(2);
  return (
    <div
      className="flex flex-col items-center rounded-2xl shadow-lg"
      style={{
        width: 260,
        minHeight: 420,
        margin: 16,
        background: '#FFF0F5',
        padding: 20,
        fontFamily: 'Poppins, sans-serif',
        justifyContent: 'space-between',
        display: 'flex'
      }}
    >
      <img
        src={`/storage/${pelicula.imagen}`}
        alt={pelicula.titulo}
        className="mb-4"
        style={{
          height: 250,
          objectFit: 'cover',
          width: '100%',
          borderRadius: 15,
          boxShadow: '0px 4px 6px rgba(0,0,0,0.1)'
        }}
      />
      <h2
        className="text-center font-bold"
        style={{ color: '#5A3F50', fontSize: '1.2rem' }}
      >
        {pelicula.titulo}
      </h2>
      <p style={{ color: '#D28B7A', fontSize: 14, margin: 0 }}>
        🎬 {pelicula.director} - {pelicula.anio_estreno}
      </p>
      <p style={{ color: '#D28B7A', fontSize: 14, margin: 0 }}>
         Stock: {pelicula.stock}
      </p>
      <div style={{ marginTop: 4, marginBottom: 4 }}>
        <p style={{ color: '#5A3F50', fontWeight: 600, fontSize: 15, margin: 0 }}>
           Alquilar: <span style={{ color: '#D28B7A' }}>{precioAlquiler}€</span>
          {descuento > 0 && (
            <span style={{ color: '#888', fontSize: 13, marginLeft: 6 }}>
              (dto. {descuento}%)
            </span>
          )}
        </p>
        <p style={{ color: '#5A3F50', fontWeight: 600, fontSize: 15, margin: 0 }}>
           Comprar: <span style={{ color: '#5A3F50' }}>{Number(pelicula.precio).toFixed(2)}€</span>
        </p>
      </div>
      <div className="flex gap-2 mt-4">
        <button
          onClick={() => onAddToCart(pelicula, 'alquilar')}
          className="btn"
          style={{
            backgroundColor: '#F1C6D1',
            color: '#5A3F50'
          }}
        >
          Alquilar
        </button>
        <button
          onClick={() => onAddToCart(pelicula, 'comprar')}
          className="btn"
          style={{
            backgroundColor: '#D28B7A',
            color: '#fff'
          }}
        >
          Comprar
        </button>
      </div>
    </div>
  );
};

export default TarjetaPelicula;