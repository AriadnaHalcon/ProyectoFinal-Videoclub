import React from 'react';
import Navbar from '@/Components/Navbar';
import { Helmet, HelmetProvider } from 'react-helmet-async';
import { CarritoProvider } from '@/Components/Carrito';
import Footer from '@/Components/Footer';

export default function AppLayout({ children }) {
  return (
    <HelmetProvider>
      <Helmet>
        
        <link
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap"
          rel="stylesheet"
        />
        <link
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
          rel="stylesheet"
        />
        <link href="/css/style.css" rel="stylesheet" />
      </Helmet>
      <CarritoProvider>
        <div
          style={{
            minHeight: '100vh',
            backgroundColor: '#FADADD',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          <Navbar />
          <main className="container py-4" style={{ flex: 1 }}>{children}</main>
          <Footer />
        </div>
      </CarritoProvider>
    </HelmetProvider>
  );
}