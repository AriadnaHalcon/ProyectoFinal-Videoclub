import React, { createContext, useContext, useState } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import ModalPagoSimulado from '@/components/ModalPagoSimulado';

export const CarritoContext = createContext();

export function useCarrito() {
  return useContext(CarritoContext);
}

export function CarritoProvider({ children }) {
  const [carrito, setCarrito] = useState([]);

  const addToCarrito = (pelicula, tipo) => {
    const itemExistente = carrito.find(
      item => item.pelicula.id_pelicula === pelicula.id_pelicula && item.tipo === tipo
    );
    if (itemExistente) {
      return false;
    } else {
      setCarrito([...carrito, { pelicula, tipo, cantidad: 1 }]);
      return true;
    }
  };

  const removeFromCarrito = (index) => {
    const nuevoCarrito = [...carrito];
    nuevoCarrito.splice(index, 1);
    setCarrito(nuevoCarrito);
  };

  const clearCarrito = () => setCarrito([]);

  return (
    <CarritoContext.Provider value={{ carrito, setCarrito, addToCarrito, removeFromCarrito, clearCarrito }}>
      {children}
    </CarritoContext.Provider>
  );
}

const Carrito = ({ onRemove, onClear, tarifaActual }) => {
  const { carrito, clearCarrito } = useCarrito();
  const [loading, setLoading] = useState(false);
  const [showPago, setShowPago] = useState(false);
  const descuento = Number(tarifaActual?.descuento) || 0;
  const total = carrito.reduce((acc, item) => {
    const precio = item.tipo === 'alquilar'
      ? item.pelicula.precio * (1 - descuento / 100)
      : item.pelicula.precio;
    return acc + precio * item.cantidad;
  }, 0);

  const handlePagar = async () => {
    setShowPago(true);
  };

  const handlePagoSimulado = async () => {
    setShowPago(false);
    setLoading(true);
    Swal.fire({
      title: 'Procesando el pago...',
      text: 'Por favor, espera mientras se realiza el pago.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    try {
      await axios.post('/carrito/guardar', {
        items: carrito.map(item => ({
          id_pelicula: item.pelicula.id_pelicula,
          cantidad: item.cantidad,
          tipo: item.tipo, 
        })),
      });
      Swal.fire({
        title: 'Pago realizado correctamente',
        icon: 'success',
        timer: 1800,
        showConfirmButton: false,
      });
      clearCarrito();
    } catch (error) {
      Swal.fire({
        title: 'Error',
        text: 'Error al guardar la compra/alquiler',
        icon: 'error',
        confirmButtonText: 'Cerrar',
      });
    } finally {
      setLoading(false);
    }
  };

  const handleVaciar = () => {
    clearCarrito();
    Swal.fire({
      title: 'Se ha vaciado el carrito correctamente',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false,
    });
  };

  return (
    <>
      <div>
        <h2 className="text-center" style={{ color: '#5A3F50', fontWeight: 'bold', fontSize: '1.1rem', marginBottom: 12 }}>
          🛒 Carrito
        </h2>
        {carrito.length === 0 ? (
          <p style={{ color: '#D28B7A', fontSize: 14, textAlign: 'center' }}>El carrito está vacío.</p>
        ) : (
          <>
            <ul style={{ listStyle: 'none', padding: 0, marginBottom: 12 }}>
              {carrito.map((item, index) => (
                <li key={index} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 14, marginBottom: 6 }}>
                  <span>
                    {item.pelicula.titulo} ({item.tipo}) x {item.cantidad} - 
                    {item.tipo === 'alquilar'
                      ? `€${(Number(item.pelicula.precio) * (1 - descuento / 100)).toFixed(2)}`
                      : `€${Number(item.pelicula.precio).toFixed(2)}`
                    }
                  </span>
                  <button
                    onClick={() => onRemove(index)}
                    className="btn"
                    style={{ background: 'none', color: '#D28B7A', fontWeight: 'bold', fontSize: 18, border: 'none', cursor: 'pointer' }}
                    title="Quitar"
                  >
                    X
                  </button>
                </li>
              ))}
            </ul>
            <p style={{ color: '#5A3F50', fontWeight: 600, marginTop: 8, fontSize: 15, textAlign: 'center' }}>
              Total: {total.toFixed(2)} €
            </p>
          </>
        )}
      </div>
      
      <div className="modal-footer" style={{ backgroundColor: '#F1C6D1', display: 'flex', justifyContent: 'space-between', borderBottomLeftRadius: 10, borderBottomRightRadius: 10, marginTop: 16 }}>
        <button
          onClick={handlePagar}
          disabled={loading || carrito.length === 0}
          className="btn btn-primary btn-comprar"
          style={{ backgroundColor: '#D28B7A', border: 'none', fontWeight: 'bold' }}
        >
          {loading ? 'Procesando...' : 'Pagar'}
        </button>
        <button
          onClick={handleVaciar}
          className="btn btn-secondary btn-alquilar"
          style={{ backgroundColor: '#F1C6D1', color: '#5A3F50', fontWeight: 'bold', border: 'none' }}
          disabled={carrito.length === 0}
        >
          Vaciar carrito
        </button>
      </div>
      <ModalPagoSimulado show={showPago} onClose={() => setShowPago(false)} onSuccess={handlePagoSimulado} />
    </>
  );
};

export default Carrito;