import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css'
import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';



import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import Error403 from './Pages/Errors/Error403';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },

    errorHandler: (error) => {
        if (error.response && error.response.status === 403) {
            return <Error403 />;
        }
        throw error;
    },
});