import React, { useState } from 'react';
import axios from 'axios';  // Importamos Axios

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [error, setError] = useState(null);  // Para manejar errores de validación

  const handlePasswordToggle = () => {
    setPasswordVisible(!passwordVisible);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Crear el objeto con los datos del formulario
    const data = { email, password };

    try {
      // Enviar los datos usando Axios
      const response = await axios.post('/login', data, {
        headers: {
          'Content-Type': 'application/json',
          // Puedes agregar otros encabezados si es necesario, como 'X-CSRF-TOKEN' para seguridad
        }
      });
      
      // Si la solicitud es exitosa, redirigir o hacer lo que sea necesario
      console.log('Login successful', response.data);
      window.location.href = '/dashboard';  // Redirigir a un dashboard o página de inicio

    } catch (error) {
      // Manejar el error
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
        {/* Email Input */}
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

        {/* Password Input */}
        <div className="input-container">
          <label htmlFor="password">Password:</label>
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

        {/* Error message */}
        {error && <p style={{ color: 'red' }}>{error}</p>}

        {/* Submit Button */}
        <button type="submit">Login</button>
      </form>

      {/* Register Link */}
      <div>
        <p>
          ¿No tienes una cuenta? <a href="/register">Regístrate aquí</a>
        </p>
      </div>
    </div>
  );
}
