<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avicontrol2025 - Sistema integral de gestión avícola para optimizar tu producción">
    <meta name="theme-color" content="#4d7c0f">
    <title>Avicontrol2025 - Sistema de Gestión Avícola</title>

    <!-- Preconectar a orígenes externos para mejorar rendimiento -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Estilos Personalizados -->
    <style>
        :root {
            --primary: #4d7c0f; /* Verde campo */
            --primary-dark: #3f6a0a; /* Verde campo oscuro para hover */
            --secondary: #b45309; /* Marrón gallina */
            --tertiary: #f59e0b; /* Amarillo huevo */
            --tertiary-dark: #d88e09; /* Amarillo huevo oscuro para hover */
            --light: #f8fafc; /* Blanco pluma */
            --dark: #334155; /* Gris oscuro */
            --accent: #f97316; /* Naranja pico */
            --background: #f1f5f9; /* Gris muy claro */
            --transition-fast: 0.3s ease;
            --transition-medium: 0.4s ease;
            --transition-slow: 0.5s ease;
            --box-shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.1);
            --box-shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
            --box-shadow-lg: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background);
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        /* Mejora de accesibilidad: enfoque visible */
        a:focus, button:focus, input:focus, textarea:focus {
            outline: 3px solid rgba(245, 158, 11, 0.5);
            outline-offset: 2px;
        }
        
        .navbar {
            background-color: var(--primary) !important;
            padding: 15px 0;
            transition: all var(--transition-medium);
            z-index: 1000;
        }
        
        .navbar.scrolled {
            padding: 8px 0;
            box-shadow: var(--box-shadow-sm);
        }
        
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--light) !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            color: var(--tertiary);
            margin-right: 10px;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: var(--light) !important;
            font-weight: 600;
            margin: 0 10px;
            position: relative;
            transition: all var(--transition-fast);
        }
        
        .nav-link::after {
            content: '';      
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background-color: var(--tertiary);
            transition: all var(--transition-fast);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link:hover {
            color: var(--tertiary) !important;
            transform: translateY(-2px);
        }
        
        /* Mejora: Indicador de página activa */
        .nav-link.active::after {
            width: 100%;
        }
        
        .nav-link.active {
            color: var(--tertiary) !important;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1560704429-1b78d0bdb05c');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(77, 124, 15, 0.7) 0%, rgba(180, 83, 9, 0.7) 100%);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: var(--light);
        }
        
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }
        
        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            font-weight: 500;
            margin-bottom: 40px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
        }
        
        .btn-primary-custom {
            background-color: var(--primary);
            color: var(--light);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all var(--transition-fast);
            box-shadow: 0 4px 15px rgba(77, 124, 15, 0.4);
        }
        
        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(51, 65, 85, 0.5);
        }
        
        .btn-secondary-custom {
            background-color: var(--tertiary);
            color: var(--dark);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all var(--transition-fast);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        
        .btn-secondary-custom:hover {
            background-color: var(--tertiary-dark);
            color: var(--dark);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.5);
        }
        
        .btn-outline-custom {
            background-color: transparent;
            color: var(--light);
            border: 2px solid var(--light);
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all var(--transition-fast);
        }
        
        .btn-outline-custom:hover {
            background-color: var(--light);
            color: var(--primary);
            transform: translateY(-5px);
        }
        
        .section-title {
            position: relative;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 50px;
            text-align: center;
            color: var(--dark);
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background-color: var(--tertiary);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .feature-card {
            background-color: var(--light);
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--box-shadow-md);
            text-align: center;
            transition: all var(--transition-medium);
            height: 100%;
            border-bottom: 5px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--tertiary));
            transform: scaleX(0);
            transform-origin: 0 50%;
            transition: transform var(--transition-medium);
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--box-shadow-lg);
            border-bottom: 5px solid var(--tertiary);
        }
        
        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            background-color: rgba(77, 124, 15, 0.1);
            border-radius: 50%;
            margin-bottom: 25px;
            transition: all var(--transition-fast);
        }
        
        .feature-card:hover .feature-icon {
            background-color: var(--primary);
            transform: rotate(10deg) scale(1.1);
        }
        
        .feature-icon i {
            font-size: 40px;
            color: var(--primary);
            transition: all var(--transition-fast);
        }
        
        .feature-card:hover .feature-icon i {
            color: var(--light);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .feature-description {
            color: var(--dark);
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .stats-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 80px 0;
            color: var(--light);
            position: relative;
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path d="M20 40 L40 20 L60 40 L80 20" stroke="rgba(255,255,255,0.05)" fill="none" stroke-width="2"/></svg>');
            background-size: 100px 100px;
            opacity: 0.2;
        }
        
        .stats-box {
            text-align: center;
            padding: 0 20px;
        }
        
        .stats-icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: var(--tertiary);
        }
        
        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            color: var(--light);
        }
        
        .stats-text {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .about-section {
            padding: 100px 0;
            background-color: var(--background);
        }
        
        .about-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--box-shadow-lg);
            position: relative;
        }
        
        .about-image::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: -20px;
            bottom: -20px;
            border: 5px solid var(--tertiary);
            border-radius: 20px;
            z-index: -1;
        }
        
        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all var(--transition-slow);
        }
        
        .about-image:hover img {
            transform: scale(1.05);
        }
        
        .about-content {
            padding: 30px;
        }
        
        .about-title {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .about-description {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            color: var(--dark);
        }
        
        .gallery-section {
            padding: 80px 0;
            background-color: var(--light);
        }
        
        .gallery-item {
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 30px;
            position: relative;
            box-shadow: var(--box-shadow-md);
            transition: all var(--transition-medium);
        }
        
        .gallery-item:hover {
            box-shadow: var(--box-shadow-lg);
            transform: translateY(-10px);
        }
        
        .gallery-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all var(--transition-slow);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(77, 124, 15, 0.8) 0%, rgba(180, 83, 9, 0.8) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all var(--transition-slow);
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-item:hover .gallery-image {
            transform: scale(1.1);
        }
        
        .gallery-title {
            color: var(--light);
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            padding: 0 15px;
        }
        
        footer {
            background-color: var(--dark);
            color: var(--light);
            padding: 80px 0 20px;
            position: relative;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--light);
        }
        
        .footer-text {
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .footer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--light);
            position: relative;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--tertiary);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all var(--transition-fast);
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: var(--tertiary);
            transform: translateX(5px);
        }
        
        .footer-contact {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .footer-contact i {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            color: var(--tertiary);
        }
        
        .footer-contact a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all var(--transition-fast);
        }
        
        .footer-contact a:hover {
            color: var(--tertiary);
        }
        
        .social-icons {
            margin-top: 25px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--light);
            margin-right: 10px;
            transition: all var(--transition-fast);
        }
        
        .social-icons a:hover {
            background-color: var(--tertiary);
            color: var(--dark);
            transform: translateY(-5px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Animaciones personalizadas */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 5s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: pulse 3s ease-in-out infinite;
        }
        
        /* Mejora: Botón de volver arriba */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary);
            color: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: var(--box-shadow-md);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-fast);
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--tertiary);
            transform: translateY(-5px);
        }
        
        /* Responsive styles */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
            }
            .section-title {
                font-size: 2rem;
            }
            .about-image::after {
                display: none;
            }
            .navbar-collapse {
                background-color: var(--primary);
                padding: 20px;
                border-radius: 10px;
                box-shadow: var(--box-shadow-md);
                margin-top: 10px;
            }
        }
        
        @media (max-width: 767px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .hero-subtitle {
                font-size: 1.2rem;
            }
            .section-title {
                font-size: 1.8rem;
            }
            .stats-number {
                font-size: 2.5rem;
            }
            .feature-card, .about-image {
                margin-bottom: 30px;
            }
            .back-to-top {
                bottom: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
        
        /* Carousel customization */
        .carousel-item {
            height: 500px;
        }
        
        .carousel-item img {
            height: 100%;
            object-fit: cover;
        }
        
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            padding: 20px;
            max-width: 80%;
            margin: 0 auto;
        }
        
        .search-bar {
            margin-bottom: 50px;
        }
        
        .search-bar .form-control {
            border-radius: 50px 0 0 50px;
            padding: 12px 25px;
            border: none;
            box-shadow: var(--box-shadow-sm);
        }
        
        .search-bar .btn {
            border-radius: 0 50px 50px 0;
            padding: 12px 25px;
            background-color: var(--primary);
            color: var(--light);
            border: none;
            transition: all var(--transition-fast);
        }
        
        .search-bar .btn:hover {
            background-color: var(--tertiary);
        }
        
        /* Mejora: Estilos para formularios */
        .form-control:focus {
            border-color: var(--tertiary);
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.25);
        }
        
        .form-label {
            font-weight: 600;
        }
        
        /* Mejora: Estilos para modales */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: var(--box-shadow-lg);
        }
        
        .modal-header {
            border-bottom: 2px solid var(--tertiary);
            padding: 20px;
        }
        
        .modal-title {
            font-weight: 700;
            color: var(--dark);
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            border-top: none;
            padding: 20px;
        }
        
        /* Mejora: Efecto de carga */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        
        .loader {
            width: 80px;
            height: 80px;
            border: 5px solid rgba(77, 124, 15, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Mejora: Tooltip personalizado */
        .custom-tooltip {
            position: relative;
            display: inline-block;
        }
        
        .custom-tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: var(--dark);
            color: var(--light);
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .custom-tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: var(--dark) transparent transparent transparent;
        }
        
        .custom-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="#">
                <i class="fas fa-feather-alt"></i> Avicontrol2025
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto animate__animated animate__fadeInRight">
                 
                <li class="nav-item d-none d-sm-inline-block">
            @auth
                @if(checkRol('avicontrol.admin'))
                    <li class="nav-item d-none d-sm-inline-block" style="margin-right: 80px;">
                        <a href="{{ route('avicontrol.admin.welcome') }}" 
                           class="nav-link @if(Route::is('avicontrol.admin.*')) active @endif">
                            Administrador
                        </a>
                    </li>
                @endif
              
            @endauth
        </li>
                    <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Galería</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Estadísticas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content animate__animated animate__zoomIn">
                <h1 class="hero-title">Avicontrol2025</h1>
                <p class="hero-subtitle">Sistema integral de gestión avícola para optimizar tu producción</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#features" class="btn btn-primary-custom">Comenzar Ahora</a>
                    <a href="#about" class="btn btn-outline-custom">Descubre Más</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Carousel Section -->
    <section class="carousel-section">
        <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1583133010904-e5c4d05ba9c8" class="d-block w-100" alt="Granja avícola" loading="lazy">
                    <div class="carousel-caption">
                        <h3 class="animate__animated animate__fadeInDown">Control de Producción</h3>
                        <p class="animate__animated animate__fadeInUp">Monitorea diariamente la postura y salud de tus aves</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7" class="d-block w-100" alt="Producción de huevos" loading="lazy">
                    <div class="carousel-caption">
                        <h3 class="animate__animated animate__fadeInDown">Gestión de Inventario</h3>
                        <p class="animate__animated animate__fadeInUp">Controla existencias de huevos y optimiza tu almacenamiento</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1551651495-0f3d3321e0e7" class="d-block w-100" alt="Tecnología avícola" loading="lazy">
                    <div class="carousel-caption">
                        <h3 class="animate__animated animate__fadeInDown">Tecnología Avanzada</h3>
                        <p class="animate__animated animate__fadeInUp">Implementa soluciones tecnológicas para mejorar la producción</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container py-5">
            <h2 class="section-title" data-aos="fade-up">Características Principales</h2>
            
            <!-- Filtro de Búsqueda -->
            <div class="search-bar" data-aos="fade-up">
                <div class="input-group">
                    <input type="text" id="feature-search" class="form-control" placeholder="Buscar características..." aria-label="Buscar características">
                    <button class="btn" type="button"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
            
            <div class="row g-4" id="features-container">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <h4 class="feature-title">Gestión de Galpones</h4>
                        <p class="feature-description">Administra múltiples galpones con información detallada sobre capacidad, dimensiones y condiciones ambientales en tiempo real.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-egg"></i>
                        </div>
                        <h4 class="feature-title">Control de Producción</h4>
                        <p class="feature-description">Registra diariamente la postura de huevos, peso promedio y calidad, generando estadísticas automáticas y proyecciones.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 class="feature-title">Gestión de Alimentos</h4>
                        <p class="feature-description">Controla el consumo de alimento, formula raciones y optimiza costos con alertas automáticas de inventario bajo.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4 class="feature-title">Salud y Bioseguridad</h4>
                        <p class="feature-description">Registra vacunaciones, tratamientos y mortalidad. Genera protocolos de bioseguridad personalizados.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="feature-title">Análisis y Reportes</h4>
                        <p class="feature-description">Visualiza estadísticas detalladas de producción, genera informes personalizados y toma decisiones basadas en datos.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon pulse">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4 class="feature-title">Alertas Inteligentes</h4>
                        <p class="feature-description">Recibe notificaciones sobre caídas en la producción, problemas de salud o cambios ambientales críticos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container py-5">
            <h2 class="section-title" data-aos="fade-up">Galería Avícola</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7" class="gallery-image" alt="Huevos Frescos" loading="lazy">
                        <div class="gallery-overlay">
                        <h5 class="gallery-title">Producción de Huevos</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1583133010904-e5c4d05ba9c8" class="gallery-image" alt="Galpón Moderno" loading="lazy">
                        <div class="gallery-overlay">
                            <h5 class="gallery-title">Galpones Modernos</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1563699182-58775c28568a" class="gallery-image" alt="Gallinas en Libertad" loading="lazy">
                        <div class="gallery-overlay">
                            <h5 class="gallery-title">Bienestar Animal</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1625945239949-a2ef1b6d42f4" class="gallery-image" alt="Clasificación de Huevos" loading="lazy">
                        <div class="gallery-overlay">
                            <h5 class="gallery-title">Clasificación Automática</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="500">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1569127959161-2b1297b2d9a2" class="gallery-image" alt="Alimentos Avícolas" loading="lazy">
                        <div class="gallery-overlay">
                            <h5 class="gallery-title">Nutrición Avanzada</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="600">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1551651495-0f3d3321e0e7" class="gallery-image" alt="Tecnología Avícola" loading="lazy">
                        <div class="gallery-overlay">
                            <h5 class="gallery-title">Automatización</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="container py-5">
            <h2 class="section-title text-light" data-aos="fade-up">Nuestros Resultados</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-box">
                        <i class="fas fa-egg stats-icon floating"></i>
                        <h3 class="stats-number" data-count="95">0</h3>
                        <p class="stats-text">% Aumento de Productividad</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-box">
                        <i class="fas fa-calendar-check stats-icon floating"></i>
                        <h3 class="stats-number" data-count="12">0</h3>
                        <p class="stats-text">Años de Experiencia</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-box">
                        <i class="fas fa-users stats-icon floating"></i>
                        <h3 class="stats-number" data-count="5000">0</h3>
                        <p class="stats-text">Granjas Satisfechas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1567450133566-8ff3f640233e" alt="Equipo Avicontrol" class="img-fluid" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-content">
                        <h2 class="about-title">Sobre Avicontrol2025</h2>
                        <p class="about-description">Desarrollado por un equipo de expertos en avicultura y tecnología, Avicontrol2025 nace con la misión de revolucionar la gestión avícola en Colombia y Latinoamérica.</p>
                        <p class="about-description">Con más de 12 años de experiencia en el sector, entendemos los desafíos que enfrentan los productores avícolas, desde pequeñas granjas familiares hasta operaciones industriales de gran escala.</p>
                        <p class="about-description">Nuestra plataforma integral combina lo mejor de la tecnología con el conocimiento práctico del campo, permitiendo a nuestros usuarios maximizar su producción, reducir costos y garantizar el bienestar animal.</p>
                        <div class="d-flex gap-3 mt-4">
                            <a href="#features" class="btn btn-primary-custom">Nuestras Soluciones</a>
                            <a href="#contact" class="btn btn-secondary-custom">Contáctanos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5" id="contact">
        <div class="container py-5">
            <h2 class="section-title" data-aos="fade-up">Contáctanos</h2>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="contact-form p-4 bg-white rounded-4 shadow">
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" placeholder="Ingresa tu nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="subject" placeholder="Asunto del mensaje" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100">Enviar Mensaje</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-info p-4 bg-white rounded-4 shadow h-100">
                        <h3 class="mb-4">Información de Contacto</h3>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                            <p class="mb-0">Calle 123 #45-67, Bogotá, Colombia</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone-alt me-3 text-primary"></i>
                            <p class="mb-0">+57 (1) 234-5678</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            <p class="mb-0">info@avicontrol2025.com</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-clock me-3 text-primary"></i>
                            <p class="mb-0">Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                        </div>
                        <div class="social-media mt-4">
                            <h4 class="mb-3">Síguenos</h4>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-primary rounded-circle" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="btn btn-outline-primary rounded-circle" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="btn btn-outline-primary rounded-circle" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="btn btn-outline-primary rounded-circle" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h3 class="footer-logo"><i class="fas fa-feather-alt"></i> Avicontrol2025</h3>
                    <p class="footer-text">Sistema integral de gestión para la industria avícola, diseñado para optimizar el control de producción, mejorar el bienestar animal y maximizar la rentabilidad.</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h4 class="footer-title">Enlaces Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Inicio</a></li>
                        <li><a href="#features">Características</a></li>
                        <li><a href="#about">Sobre Nosotros</a></li>
                        <li><a href="#contact">Contacto</a></li>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h4 class="footer-title">Servicios</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Gestión de Galpones</a></li>
                        <li><a href="#features">Control de Producción</a></li>
                        <li><a href="#features">Inventario de Alimentos</a></li>
                        <li><a href="#features">Análisis de Datos</a></li>
                        <li><a href="#contact">Soporte Técnico</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Contacto</h4>
                    <div class="footer-contact">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Calle 123 #45-67, Bogotá, Colombia</p>
                    </div>
                    <div class="footer-contact">
                        <i class="fas fa-phone-alt"></i>
                        <p><a href="tel:+5712345678">+57 (1) 234-5678</a></p>
                    </div>
                    <div class="footer-contact">
                        <i class="fas fa-envelope"></i>
                        <p><a href="mailto:info@avicontrol2025.com">info@avicontrol2025.com</a></p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <span id="current-year"></span> Avicontrol2025. Todos los derechos reservados. Desarrollado por SENA.</p>
            </div>
        </div>
    </footer>




    <!-- Back to top button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" aria-label="Volver arriba"><i class="fas fa-arrow-up"></i></a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            preloader.classList.add('fade-out');
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        });
        
        // Inicializar AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 100,
            disable: 'mobile'
        });
        
        // Contador para estadísticas
        const startCounters = () => {
            $('.stats-number').each(function () {
                const $this = $(this);
                const countTo = $this.attr('data-count');
                
                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
        };
        
        // Iniciar contadores cuando sean visibles
        const statsSection = document.querySelector('#stats');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        if (statsSection) {
            observer.observe(statsSection);
        }
        
        // Navbar cambia de color al hacer scroll
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.navbar').addClass('scrolled');
                $('.back-to-top').addClass('active');
            } else {
                $('.navbar').removeClass('scrolled');
                $('.back-to-top').removeClass('active');
            }
        });
        
        // Smooth scroll para enlaces
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                if (this.getAttribute('href') !== '#' && 
                    !this.getAttribute('data-bs-toggle') && 
                    !this.getAttribute('data-bs-dismiss')) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Activar navegación activa según la sección visible
        const sections = document.querySelectorAll('section[id]');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });
            
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
        
        // Filtro de características
        document.getElementById('feature-search').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const featureCards = document.querySelectorAll('#features-container .feature-card');
            
            featureCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const column = card.closest('.col-md-4');
                if (text.includes(value)) {
                    column.style.display = '';
                } else {
                    column.style.display = 'none';
                }
            });
        });
        
        // Alerta de bienvenida
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                Swal.fire({
                    title: '¡Bienvenido a Avicontrol2025!',
                    text: 'El sistema integral de gestión avícola más avanzado',
                    icon: 'success',
                    confirmButtonColor: '#4d7c0f',
                    background: '#f8fafc',
                    backdrop: `rgba(77, 124, 15, 0.4)`,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            }, 1500);
        });
        
        // Back to top button
        document.querySelector('.back-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Animations for carousel
        const carousel = document.getElementById('mainCarousel');
        if (carousel) {
            carousel.addEventListener('slide.bs.carousel', function () {
                document.querySelectorAll('.carousel-item h3').forEach(el => {
                    el.classList.remove('animate__animated', 'animate__fadeInDown');
                });
                document.querySelectorAll('.carousel-item p').forEach(el => {
                    el.classList.remove('animate__animated', 'animate__fadeInUp');
                });
                
                setTimeout(function() {
                    const activeItem = document.querySelector('.carousel-item.active');
                    if (activeItem) {
                        const heading = activeItem.querySelector('h3');
                        const paragraph = activeItem.querySelector('p');
                        
                        if (heading) heading.classList.add('animate__animated', 'animate__fadeInDown');
                        if (paragraph) paragraph.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                }, 100);
            });
        }
        
        // Validación de formularios
        const validateForm = (formId) => {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                const inputs = form.querySelectorAll('input[required], textarea[required]');
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (formId === 'registerForm') {
                    const password = document.getElementById('registerPassword');
                    const confirmPassword = document.getElementById('registerConfirmPassword');
                    
                    if (password.value !== confirmPassword.value) {
                        isValid = false;
                        confirmPassword.classList.add('is-invalid');
                        
                        Swal.fire({
                            title: 'Error',
                            text: 'Las contraseñas no coinciden',
                            icon: 'error',
                            confirmButtonColor: '#4d7c0f'
                        });
                    }
                }
                
                if (isValid) {
                    Swal.fire({
                        title: 'Éxito',
                        text: formId === 'contactForm' ? 'Tu mensaje ha sido enviado' : 
                              formId === 'loginForm' ? 'Has iniciado sesión correctamente' : 
                              'Te has registrado correctamente',
                        icon: 'success',
                        confirmButtonColor: '#4d7c0f'
                    });
                    
                    form.reset();
                    
                    if (formId === 'loginForm' || formId === 'registerForm') {
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(formId === 'loginForm' ? 'loginModal' : 'registerModal'));
                            modal.hide();
                        }, 1500);
                    }
                }
            });
        };
        
        validateForm('contactForm');
        validateForm('loginForm');
        validateForm('registerForm');
        
        // Año actual en el footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Lazy loading para imágenes
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.src;
            });
        } else {
            // Fallback para navegadores que no soportan lazy loading
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
        }
    </script>
</body>
</html>
