import React, { useState } from 'react';

export default function ModalPagoSimulado({ show, onClose, onSuccess }) {
  const [numero, setNumero] = useState('');
  const [cvv, setCvv] = useState('');
  const [caducidad, setCaducidad] = useState('');
  const [error, setError] = useState('');

  const validar = () => {
    if (!/^\d{16}$/.test(numero)) {
      setError('El número de tarjeta debe tener 16 dígitos.');
      return false;
    }
    if (!/^\d{3}$/.test(cvv)) {
      setError('El CVV debe tener 3 dígitos.');
      return false;
    }
    if (!/^\d{4}-\d{2}$/.test(caducidad)) {
      setError('La fecha debe tener formato AAAA-MM.');
      return false;
    }
    // Validar que la fecha no sea anterior al mes actual
    const [anio, mes] = caducidad.split('-');
    const ahora = new Date();
    const fechaTarjeta = new Date(parseInt(anio, 10), parseInt(mes, 10) - 1);
    if (fechaTarjeta < new Date(ahora.getFullYear(), ahora.getMonth())) {
      setError('La tarjeta está caducada.');
      return false;
    }
    setError('');
    return true;
  };

  const handlePagar = (e) => {
    e.preventDefault();
    if (validar()) {
      onSuccess();
    }
  };

  if (!show) return null;

  return (
    <div style={{ position: 'fixed', top: 0, left: 0, width: '100vw', height: '100vh', background: 'rgba(0,0,0,0.3)', zIndex: 3000, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div className="modal-dialog" style={{ zIndex: 3001, minWidth: 350 }}>
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">Pago con tarjeta</h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handlePagar}>
            <div className="modal-body">
              <div className="mb-3">
                <label className="form-label">Número de tarjeta</label>
                <input
                  type="text"
                  className="form-control"
                  maxLength={16}
                  value={numero}
                  onChange={e => setNumero(e.target.value.replace(/[^0-9]/g, ''))}
                  placeholder="1234 5678 9012 3456"
                />
              </div>
              <div className="mb-3">
                <label className="form-label">CVV</label>
                <input
                  type="text"
                  className="form-control"
                  maxLength={3}
                  value={cvv}
                  onChange={e => setCvv(e.target.value.replace(/[^0-9]/g, ''))}
                  placeholder="123"
                />
              </div>
              <div className="mb-3">
                <label className="form-label">Fecha de caducidad</label>
                <input
                  type="month"
                  className="form-control"
                  value={caducidad}
                  onChange={e => setCaducidad(e.target.value)}
                  min={new Date().toISOString().slice(0,7)}
                />
              </div>
              {error && <div className="text-danger mt-2">{error}</div>}
            </div>
            <div className="modal-footer">
              <button type="submit" className="btn btn-primary">Pagar</button>
              <button type="button" className="btn btn-secondary" onClick={onClose}>Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
