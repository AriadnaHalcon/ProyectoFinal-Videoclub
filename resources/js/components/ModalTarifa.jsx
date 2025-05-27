import React from 'react';

export default function ModalTarifa({ tarifaActual, tarifas = [], onSubmit, data, setData, processing, errors, success, error, onClose }) {
    return (
        <div
            className="modal-content"
            style={{
                backgroundColor: '#FADADD',
                borderRadius: 10,
                padding: 20,
                border: 'none'
            }}
        >
            <form onSubmit={onSubmit}>
                <div
                    className="modal-header"
                    style={{
                        backgroundColor: '#F1C6D1',
                        color: '#5A3F50',
                        borderBottom: '2px solid #D28B7A',
                        borderTopLeftRadius: 10,
                        borderTopRightRadius: 10
                    }}
                >
                    <h5 className="modal-title" style={{ fontWeight: 'bold' }}>
                        Mi Tarifa
                    </h5>
                    <button type="button" className="btn-close" aria-label="Close" onClick={onClose}></button>
                </div>
                <div
                    className="modal-body"
                    style={{
                        fontSize: 14,
                        color: '#5A3F50'
                    }}
                >
                    {success && <div className="alert alert-success">{success}</div>}
                    {error && <div className="alert alert-danger">{error}</div>}
                    <p>
                        Tu tarifa actual es: <b>{tarifaActual ? tarifaActual.nombre : 'Sin tarifa'}</b>
                    </p>
                    <div className="mb-3">
                        <label className="form-label">Selecciona la tarifa a la que deseas cambiar</label>
                        <select
                            name="id_tarifa"
                            value={data.id_tarifa}
                            onChange={e => setData('id_tarifa', e.target.value)}
                            className="form-select"
                        >
                            {tarifas.map(tarifa => (
                                <option key={tarifa.id_tarifa} value={tarifa.id_tarifa}>
                                    {tarifa.nombre} ({tarifa.descuento}% descuento)
                                </option>
                            ))}
                        </select>
                        {errors.id_tarifa && <div className="text-danger">{errors.id_tarifa}</div>}
                    </div>
                </div>
                <div
                    className="modal-footer"
                    style={{
                        backgroundColor: '#F1C6D1',
                        display: 'flex',
                        justifyContent: 'space-between',
                        borderBottomLeftRadius: 10,
                        borderBottomRightRadius: 10
                    }}
                >
                    <button
                        type="submit"
                        className="btn btn-primary"
                        style={{
                            backgroundColor: '#D28B7A',
                            color: '#fff',
                            fontWeight: 'bold',
                            border: 'none'
                        }}
                        disabled={processing || !data.id_tarifa}
                    >
                        Cambiar tarifa
                    </button>
                    <button
                        type="button"
                        className="btn btn-secondary"
                        style={{
                            backgroundColor: '#F1C6D1',
                            color: '#5A3F50',
                            fontWeight: 'bold',
                            border: 'none'
                        }}
                        onClick={onClose}
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    );
}