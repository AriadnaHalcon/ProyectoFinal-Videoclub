import Checkbox from '@/components/Checkbox';
import InputError from '@/components/InputError';
import InputLabel from '@/components/InputLabel';
import TextInput from '@/components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';  // <--- Importa router aquí

import { useState } from 'react';
import PrimaryButton from '@/components/PrimaryButton';

export default function Login() {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
    });

    // Estado para mostrar/ocultar la contraseña
    const [showPassword, setShowPassword] = useState(false);

    // Manejo del formulario
    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
            onError: () => {
                // Forzar scroll arriba para ver errores
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
        });
    };

    // Alternar la visibilidad de la contraseña
    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    return (
        <div className="form-container" >
            <Head>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
                <link href="/css/login.css" rel="stylesheet" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
            </Head>
            <h1 style={{ textAlign: 'center' }}>Login</h1>
            <form onSubmit={submit}>
                {/* Mostrar error general del backend (por ejemplo, credenciales incorrectas) */}
                {/* {errors.message && (
                    <div style={{ color: 'red', marginBottom: 12, textAlign: 'center', fontWeight: 'bold' }}>{errors.message}</div>
                )} */}

                <div className="input-container" >
                    <label htmlFor="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    {errors.email && <div style={{ color: 'red', marginTop: 4 }}>{errors.email}</div>}
                </div>

                <div className="input-container">
                    <label htmlFor="password">Contraseña:</label>
                    <div style={{ position: 'relative' }}>
                        <input
                            type={showPassword ? 'text' : 'password'}
                            id="password"
                            name="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            required
                            style={{ width: '100%', paddingRight: '30px' }}
                        />
                        <i
                            className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}
                            onClick={togglePasswordVisibility}
                            style={{
                                position: 'absolute',
                                right: '10px', 
                                top: '50%',
                                transform: 'translateY(-50%)',
                                cursor: 'pointer',
                            }}
                        ></i>
                    </div>
                    {errors.password && <div style={{ color: 'red', marginTop: 4 }}>{errors.password}</div>}
                </div>

                <button type="submit" disabled={processing}>Login</button>
            </form>

            <div style={{ textAlign: 'center', marginTop: '20px' }}>
                <p>No tienes una cuenta? <Link href={route('register')}>Regístrate aquí</Link></p>
            </div>
        </div>
        
    );
}
