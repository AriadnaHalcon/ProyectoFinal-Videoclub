import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Helmet } from 'react-helmet-async';
import AppLayout from '@/Layouts/AppLayout';

Inicio.layout = (page) => <AppLayout>{page}</AppLayout>;

export default function Inicio() {
    const { auth } = usePage().props;

    return (
        <>
            {/* Uso la etiqueta "Helmet" para gestionar las etiquetas <head> */}
            <Helmet>
                <title>Bienvenido al Videoclub</title>
                <link
                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
                    rel="stylesheet"
                />
                <link
                    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap"
                    rel="stylesheet"
                />
                <link href="/css/style.css" rel="stylesheet" />
            </Helmet>

            {/* Contenido principal */}
            <div className="container-welcome py-4 text-center">
                <h1 className="text-center mt-4">Bienvenido al Videoclub</h1>
                <p className="text-center">Aquí podrás gestionar la base de datos del Videoclub.</p>
                <p className="text-center">Selecciona una opción en el menú.</p>
                <img
                    src="/images/videoclub4.jfif"
                    alt="Bienvenido al Videoclub"
                    className="img-fluid rounded shadow-lg centered-image"
                />
            </div>
        </>
    );
}