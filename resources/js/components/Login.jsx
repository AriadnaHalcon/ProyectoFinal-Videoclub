import React, { useState } from 'react';
import axios from 'axios';  

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [error, setError] = useState(null); 

  const handlePasswordToggle = () => {
    setPasswordVisible(!passwordVisible);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Crea el objeto con los datos del formulario
    const data = { email, password };

    try {
      // Enviar los datos con Axios
      const response = await axios.post('/login', data, {
        headers: {
          'Content-Type': 'application/json',
        }
      });
      
      console.log('Login successful', response.data);
      window.location.href = '/dashboard';

    } catch (error) {
      // Manejo del error
      if (error.response) {
        setError(error.response.data.message || 'Error en el inicio de sesión');
      } else {
        setError('Error en la conexión al servidor');
      }
    }
  };

  return (
    <div className="form-container">
      <h1 style={{ textAlign: 'center' }}>Login</h1>
      <form onSubmit={handleSubmit}>
        {/* Input para el Email */}
        <div className="input-container">
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            name="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        {/* Input de contraseña */}
        <div className="input-container">
          <label htmlFor="password">Contraseña:</label>
          <input
            type={passwordVisible ? 'text' : 'password'}
            id="password"
            name="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          <i
            className={`fas fa-eye ${passwordVisible ? 'fa-eye-slash' : ''} toggle-password`}
            onClick={handlePasswordToggle}
          />
        </div>

        {/* Mensaje de error */}
        {error && <p style={{ color: 'red' }}>{error}</p>}

        {/* Botón de envío */}
        <button type="submit">Login</button>
      </form>

      {/* Link de registro */}
      <div>
        <p>
          ¿No tienes una cuenta? <a href="/register">Regístrate aquí</a>
        </p>
      </div>
    </div>
  );
}
