import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Register({ tarifas = [] }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        dni: '',
        direccion: '',
        telefono: '',
        tarifa_id: tarifas.length > 0 ? tarifas[0].id : '',
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const togglePasswordVisibility = (type) => {
        if (type === 'password') {
            setShowPassword(!showPassword);
        } else {
            setShowConfirmPassword(!showConfirmPassword);
        }
    };

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <>
            <head>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
                <link href="/css/register.css" rel="stylesheet" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
            </head>
            <div className="form-container">
                <h1 className="text-center">Register</h1>
                <form onSubmit={submit}>
                    {/* Mostrar errores generales al final del formulario */}
                    {Object.keys(errors).length > 0 && (
                        <div style={{ color: 'red', marginBottom: 12, textAlign: 'center', fontWeight: 'bold' }}>
                            {Object.values(errors).map((err, idx) => (
                                <div key={idx}>{err}</div>
                            ))}
                        </div>
                    )}

                    <div className="input-container">
                        <InputLabel htmlFor="name" value="Nombre" />
                        <TextInput
                            id="name"
                            name="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>

                    <div className="input-container">
                        <InputLabel htmlFor="dni" value="DNI" />
                        <TextInput
                            id="dni"
                            name="dni"
                            value={data.dni}
                            onChange={(e) => setData('dni', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <InputError message={errors.dni} className="mt-2" />
                    </div>

                    <div className="input-container">
                        <InputLabel htmlFor="direccion" value="Dirección" />
                        <TextInput
                            id="direccion"
                            name="direccion"
                            value={data.direccion}
                            onChange={(e) => setData('direccion', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <InputError message={errors.direccion} className="mt-2" />
                    </div>

                    <div className="input-container">
                        <InputLabel htmlFor="telefono" value="Teléfono" />
                        <TextInput
                            id="telefono"
                            name="telefono"
                            value={data.telefono}
                            onChange={(e) => setData('telefono', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <InputError message={errors.telefono} className="mt-2" />
                    </div>

                    {/* <div className="input-container">
                        <InputLabel htmlFor="tarifa_id" value="Tarifa" />
                        <select
                            id="tarifa_id"
                            name="tarifa_id"
                            value={data.tarifa_id}
                            onChange={(e) => setData('tarifa_id', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        >
                            {tarifas.map((tarifa) => (
                                <option key={tarifa.id} value={tarifa.id}>
                                    {tarifa.nombre}
                                </option>
                            ))}
                        </select>
                        <InputError message={errors.tarifa_id} className="mt-2" />
                    </div> */}

                    <div className="input-container">
                        <InputLabel htmlFor="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    <div className="input-container">
                        <InputLabel htmlFor="password" value="Password" />
                        <TextInput
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            name="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <i
                            className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} toggle-password`}
                            onClick={() => togglePasswordVisibility('password')}
                        />
                        <InputError message={errors.password} className="mt-2" />
                    </div>

                    <div className="input-container">
                        <InputLabel htmlFor="password_confirmation" value="Confirm Password" />
                        <TextInput
                            id="password_confirmation"
                            type={showConfirmPassword ? 'text' : 'password'}
                            name="password_confirmation"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            required
                            className="w-full p-2 border rounded"
                        />
                        <i
                            className={`fas ${showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'} toggle-password`}
                            onClick={() => togglePasswordVisibility('confirmPassword')}
                        />
                        <InputError message={errors.password_confirmation} className="mt-2" />
                    </div>

                    <div className="mt-4 flex items-center text-align-center">
                        <Link
                            href={route('login')}
                            className="text-sm text-gray-600 underline hover:text-gray-900"
                        >
                            ¿Ya estás registrado?
                        </Link>
                    </div>
                    <button type="submit" style={{ marginTop: '20px' }}>Register</button>
                </form>
                {errors.direccion && <div style={{ color: 'red', marginTop: 4 }}>{errors.direccion}</div>}
                {errors.telefono && <div style={{ color: 'red', marginTop: 4 }}>{errors.telefono}</div>}
                {errors.dni && <div style={{ color: 'red', marginTop: 4 }}>{errors.dni}</div>}
                {errors.email && <div style={{ color: 'red', marginTop: 4 }}>{errors.email}</div>}
                {errors.password && <div style={{ color: 'red', marginTop: 4 }}>{errors.password}</div>}
                {errors.password_confirmation && <div style={{ color: 'red', marginTop: 4 }}>{errors.password_confirmation}</div>}
            </div>
        </>
    );
}