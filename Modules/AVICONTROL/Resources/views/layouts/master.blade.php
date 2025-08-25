<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avicontrol2025 - Sistema integral de gestión avícola para optimizar tu producción">
    <meta name="theme-color" content="#059669">
    <title>🐓 Avicontrol2025 - Revolución Avícola Digital</title>

    <!-- Preconectar a orígenes externos para mejorar rendimiento -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Fuentes modernas -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Estilos Revolucionarios -->
    <style>
        :root {
            /* Paleta de colores moderna y vibrante */
            --primary: #059669; /* Verde esmeralda */
            --primary-dark: #047857; /* Verde esmeralda oscuro */
            --primary-light: #10b981; /* Verde esmeralda claro */
            --secondary: #f59e0b; /* Ámbar dorado */
            --secondary-dark: #d97706; /* Ámbar oscuro */
            --accent: #8b5cf6; /* Violeta */
            --accent-dark: #7c3aed; /* Violeta oscuro */
            --danger: #ef4444; /* Rojo coral */
            --warning: #f59e0b; /* Ámbar */
            --success: #10b981; /* Verde éxito */
            --info: #3b82f6; /* Azul información */
            
            /* Tonos neutros sofisticados */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Gradientes espectaculares */
            --gradient-primary: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            --gradient-secondary: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fde047 100%);
            --gradient-accent: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 50%, #c4b5fd 100%);
            --gradient-dark: linear-gradient(135deg, #1f2937 0%, #374151 50%, #4b5563 100%);
            --gradient-hero: linear-gradient(135deg, rgba(5, 150, 105, 0.95) 0%, rgba(16, 185, 129, 0.9) 50%, rgba(52, 211, 153, 0.85) 100%);
            
            /* Sombras modernas */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --shadow-glow: 0 0 30px rgba(5, 150, 105, 0.3);
            --shadow-glow-lg: 0 0 60px rgba(5, 150, 105, 0.4);
            
            /* Transiciones fluidas */
            --transition-fast: all 0.15s ease-in-out;
            --transition-normal: all 0.3s ease-in-out;
            --transition-slow: all 0.5s ease-in-out;
            --transition-bounce: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            
            /* Tipografía */
            --font-primary: 'Inter', system-ui, -apple-system, sans-serif;
            --font-display: 'Poppins', system-ui, -apple-system, sans-serif;
            
            /* Espaciado */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            --space-3xl: 4rem;
            
            /* Bordes */
            --border-radius-sm: 0.375rem;
            --border-radius: 0.5rem;
            --border-radius-md: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
            --border-radius-2xl: 2rem;
            --border-radius-full: 9999px;
        }
        
        /* Reset y configuración base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-primary);
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
            scroll-behavior: smooth;
            font-size: 16px;
            font-weight: 400;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
        }
        
        h1 { font-size: clamp(2.5rem, 5vw, 4rem); }
        h2 { font-size: clamp(2rem, 4vw, 3rem); }
        h3 { font-size: clamp(1.5rem, 3vw, 2rem); }
        h4 { font-size: clamp(1.25rem, 2.5vw, 1.5rem); }
        
        /* Estados de enfoque mejorados */
        a:focus, button:focus, input:focus, textarea:focus, select:focus {
            outline: 3px solid rgba(5, 150, 105, 0.5);
            outline-offset: 2px;
            border-radius: var(--border-radius-sm);
        }
        
        /* Elementos interactivos */
        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition-fast);
        }
        
        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        /* Navbar revolucionaria */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            transition: var(--transition-normal);
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }
        
        .navbar.scrolled {
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: var(--shadow-lg);
        }
        
        .navbar-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--gray-900) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition-fast);
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            color: var(--primary) !important;
        }
        
        .navbar-brand i {
            color: var(--primary);
            font-size: 2rem;
            filter: drop-shadow(0 0 10px rgba(5, 150, 105, 0.3));
            animation: pulse-glow 2s infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .nav-link {
            color: var(--gray-700) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            position: relative;
            transition: var(--transition-fast);
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius-full);
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-full);
            opacity: 0;
            transform: scale(0.8);
            transition: var(--transition-fast);
            z-index: -1;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            opacity: 1;
            transform: scale(1);
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
        }
        
        /* Hero section espectacular */
        .hero-section {
            min-height: 100vh;
            background: 
                linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%),
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23059669' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .hero-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
            letter-spacing: -0.02em;
            animation: slide-up 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: clamp(1.25rem, 3vw, 1.75rem);
            font-weight: 400;
            margin-bottom: 3rem;
            color: var(--gray-600);
            line-height: 1.6;
            animation: slide-up 1s ease-out 0.2s both;
        }
        
        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Botones modernos y atractivos */
        .btn-primary-custom {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: var(--border-radius-full);
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition-bounce);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: none;
            letter-spacing: 0.5px;
            animation: slide-up 1s ease-out 0.4s both;
        }
        
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition-slow);
        }
        
        .btn-primary-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-glow-lg);
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-secondary-custom {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 1rem 2.5rem;
            border-radius: var(--border-radius-full);
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition-bounce);
            box-shadow: var(--shadow-md);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            animation: slide-up 1s ease-out 0.6s both;
        }
        
        .btn-secondary-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-glow);
        }
        
        .btn-outline-custom {
            background: rgba(255, 255, 255, 0.1);
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
            padding: 1rem 2.5rem;
            border-radius: var(--border-radius-full);
            font-weight: 600;
            transition: var(--transition-fast);
            backdrop-filter: blur(10px);
        }
        
        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
        }
        
        /* Títulos de sección modernos */
        .section-title {
            position: relative;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 4rem;
            text-align: center;
            color: var(--gray-900);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 6px;
            background: var(--gradient-primary);
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--border-radius-full);
            box-shadow: var(--shadow-glow);
        }
        
        .section-subtitle {
            font-size: clamp(1.125rem, 2vw, 1.25rem);
            color: var(--gray-600);
            margin-bottom: 3rem;
            text-align: center;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Cards de características ultra modernas */
        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius-2xl);
            box-shadow: var(--shadow-md);
            text-align: center;
            transition: var(--transition-bounce);
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            opacity: 0;
            transition: var(--transition-normal);
            z-index: 0;
        }
        
        .feature-card:hover::before {
            opacity: 0.03;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary);
        }
        
        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-2xl);
            margin-bottom: 2rem;
            transition: var(--transition-bounce);
            position: relative;
            z-index: 1;
            box-shadow: var(--shadow-glow);
        }
        
        .feature-card:hover .feature-icon {
            transform: rotate(5deg) scale(1.1);
            box-shadow: var(--shadow-glow-lg);
        }
        
        .feature-icon i {
            font-size: 2.5rem;
            color: white;
            transition: var(--transition-fast);
        }
        
        .feature-card:hover .feature-icon i {
            transform: scale(1.1);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
            position: relative;
            z-index: 1;
        }
        
        .feature-description {
            color: var(--gray-600);
            font-size: 1rem;
            line-height: 1.7;
            position: relative;
            z-index: 1;
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
        
        /* Efectos adicionales y utilidades */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-glow {
            animation: pulse-glow-effect 2s infinite;
        }
        
        @keyframes pulse-glow-effect {
            0%, 100% { box-shadow: var(--shadow-glow); }
            50% { box-shadow: var(--shadow-glow-lg); }
        }
        
        /* Dashboard Mockup Interactivo */
        .dashboard-mockup {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid var(--gray-200);
            min-height: 300px;
        }
        
        .mockup-header {
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 0.75rem;
        }
        
        .chart-bar {
            width: 20px;
            transition: height 0.8s ease-in-out;
            position: relative;
            opacity: 0;
        }
        
        .animated-bar {
            animation: slideUp 1s ease-out forwards;
        }
        
        @keyframes slideUp {
            from {
                height: 0 !important;
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .metric-value {
            font-size: 1.25rem;
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Gradientes para botones */
        .bg-gradient-primary {
            background: var(--gradient-primary) !important;
        }
        
        .bg-gradient-secondary {
            background: var(--gradient-secondary) !important;
        }
        
        /* Efectos de hover mejorados */
        .dashboard-mockup:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-2xl);
            transition: all 0.3s ease;
        }
        
        .chart-bar:hover {
            transform: scaleY(1.1);
            filter: brightness(1.2);
        }
        
        /* Sección de Datos en Tiempo Real */
        .real-time-metric {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-xl);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition-normal);
        }
        
        .real-time-metric:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .live-data-simulator {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-height: 300px;
        }
        
        .live-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: livePulse 2s infinite;
        }
        
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        
        .live-chart {
            height: 80px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
        }
        
        .chart-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            animation: chartWave 3s ease-in-out infinite;
        }
        
        @keyframes chartWave {
            0%, 100% { height: 40%; }
            25% { height: 70%; }
            50% { height: 45%; }
            75% { height: 85%; }
        }
        
        .alert-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border: none;
            border-radius: var(--border-radius);
        }
        
        /* Contador animado para métricas */
        .metric-number {
            display: inline-block;
            min-width: 80px;
        }
        
        /* Testimonios Modernos */
        .testimonial-card {
            background: white;
            border-radius: var(--border-radius-2xl);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: var(--transition-bounce);
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition-normal);
        }
        
        .testimonial-card:hover::before {
            transform: scaleX(1);
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary);
        }
        
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .testimonial-avatar {
            flex-shrink: 0;
        }
        
        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-glow);
        }
        
        .bg-gradient-accent {
            background: var(--gradient-accent) !important;
        }
        
        .testimonial-rating {
            margin-top: 0.25rem;
        }
        
        .testimonial-rating i {
            font-size: 0.875rem;
            margin-right: 0.125rem;
        }
        
        .testimonial-content {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .testimonial-content::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: -10px;
            font-size: 3rem;
            color: var(--primary);
            opacity: 0.2;
            font-family: Georgia, serif;
        }
        
        .testimonial-stats {
            display: flex;
            gap: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .stat-label {
            display: block;
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 0.25rem;
        }
        
        /* Galería Visual Innovadora */
        .visual-card {
            background: white;
            border-radius: var(--border-radius-2xl);
            padding: 0;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: var(--transition-bounce);
            height: 100%;
            overflow: hidden;
        }
        
        .visual-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary);
        }
        
        .visual-icon-container {
            position: relative;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .visual-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
        }
        
        .eggs-pattern {
            background: 
                radial-gradient(circle at 20% 20%, var(--secondary) 3px, transparent 3px),
                radial-gradient(circle at 80% 80%, var(--secondary) 2px, transparent 2px),
                radial-gradient(circle at 40% 70%, var(--secondary) 2px, transparent 2px);
            background-size: 40px 40px, 30px 30px, 35px 35px;
            animation: patternMove 20s linear infinite;
        }
        
        .barn-pattern {
            background: 
                linear-gradient(45deg, var(--primary) 1px, transparent 1px),
                linear-gradient(-45deg, var(--primary) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: patternMove 15s linear infinite;
        }
        
        .wellness-pattern {
            background: 
                radial-gradient(circle at 50% 50%, var(--success) 1px, transparent 1px);
            background-size: 15px 15px;
            animation: patternPulse 3s ease-in-out infinite;
        }
        
        .sorting-pattern {
            background: 
                repeating-linear-gradient(
                    45deg,
                    var(--info),
                    var(--info) 2px,
                    transparent 2px,
                    transparent 10px
                );
            animation: patternSlide 10s linear infinite;
        }
        
        .nutrition-pattern {
            background: 
                radial-gradient(circle at 25% 25%, var(--success) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, var(--success) 1px, transparent 1px);
            background-size: 25px 25px, 15px 15px;
            animation: patternGrow 8s ease-in-out infinite;
        }
        
        .tech-pattern {
            background: 
                conic-gradient(from 0deg at 50% 50%, var(--accent) 0deg, transparent 60deg, var(--accent) 120deg, transparent 180deg);
            background-size: 30px 30px;
            animation: patternRotate 12s linear infinite;
        }
        
        @keyframes patternMove {
            0% { background-position: 0 0, 0 0, 0 0; }
            100% { background-position: 40px 40px, -30px -30px, 35px -35px; }
        }
        
        @keyframes patternPulse {
            0%, 100% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.1); opacity: 0.2; }
        }
        
        @keyframes patternSlide {
            0% { background-position: 0 0; }
            100% { background-position: 20px 20px; }
        }
        
        @keyframes patternGrow {
            0%, 100% { background-size: 25px 25px, 15px 15px; }
            50% { background-size: 30px 30px, 20px 20px; }
        }
        
        @keyframes patternRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .visual-icon {
            position: relative;
            z-index: 2;
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: var(--shadow-glow);
            animation: iconFloat 4s ease-in-out infinite;
        }
        
        @keyframes iconFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }
        
        .visual-counter {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-full);
            font-weight: 800;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
            z-index: 3;
        }
        
        .visual-content {
            padding: 1.5rem;
        }
        
        .visual-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
        }
        
        .visual-description {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .visual-progress {
            background: var(--gray-200);
            border-radius: var(--border-radius-full);
            height: 6px;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-full);
            transition: width 2s ease-in-out;
            position: relative;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .visual-percentage {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Preloader simplificado */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .preloader.fade-out {
            display: none !important;
        }
        
        .loader {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top-color: #059669;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Back to top button moderno */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--border-radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-xl);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-bounce);
            border: none;
            cursor: pointer;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: var(--shadow-glow-lg);
        }
        
        /* Responsive styles modernos */
        @media (max-width: 991px) {
            .hero-content {
                padding: 0 1.5rem;
            }
            
            .section-title {
                margin-bottom: 3rem;
            }
            
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                padding: 1.5rem;
                border-radius: var(--border-radius-xl);
                box-shadow: var(--shadow-xl);
                margin-top: 1rem;
                border: 1px solid var(--gray-200);
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 767px) {
            .hero-content {
                padding: 0 1rem;
            }
            
            .hero-subtitle {
                margin-bottom: 2rem;
            }
            
            .btn-primary-custom,
            .btn-secondary-custom {
                padding: 0.875rem 2rem;
                font-size: 1rem;
                margin-bottom: 1rem;
            }
            
            .feature-card {
                padding: 2rem;
                margin-bottom: 1.5rem;
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                margin-bottom: 1.5rem;
            }
            
            .feature-icon i {
                font-size: 2rem;
            }
            
            .back-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand i {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero-content {
                padding: 0 0.75rem;
            }
            
            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                justify-content: center;
            }
            
            .feature-card {
                padding: 1.5rem;
            }
            
            .section-title {
                margin-bottom: 2rem;
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

    <!-- Navbar Revolucionaria -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="#">
                <i class="fas fa-egg"></i>
                <span class="gradient-text">Avicontrol2025</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto animate__animated animate__fadeInRight">
                    @auth
                        @if(checkRol('avicontrol.admin'))
                            <li class="nav-item">
                                <a href="{{ route('avicontrol.admin.welcome') }}" 
                                   class="nav-link @if(Route::is('avicontrol.admin.*')) active @endif">
                                    <i class="fas fa-user-shield me-1"></i>
                                    Panel Admin
                                </a>
                            </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fas fa-home me-1"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="fas fa-star me-1"></i>
                            Características
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#technology">
                            <i class="fas fa-cogs me-1"></i>
                            Tecnología
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">
                            <i class="fas fa-chart-line me-1"></i>
                            Resultados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>
                            Contacto
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section Espectacular -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="floating-element">
                    <h1 class="hero-title">
                        El Futuro de la 
                        <span class="gradient-text">Avicultura</span>
                        está Aquí
                    </h1>
                </div>
                <p class="hero-subtitle">
                    Revoluciona tu granja con Avicontrol2025: IA, automatización y análisis avanzado 
                    para maximizar tu producción y rentabilidad como nunca antes.
                </p>
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <a href="#features" class="btn btn-primary-custom">
                        <i class="fas fa-rocket"></i>
                        Comenzar Ahora
                    </a>
                    <a href="#technology" class="btn btn-secondary-custom">
                        <i class="fas fa-play-circle"></i>
                        Ver Demo
                    </a>
                </div>
                
                <!-- Estadísticas rápidas -->
                <div class="row mt-5 g-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="h2 gradient-text fw-bold mb-1">+95%</div>
                            <div class="text-muted">Aumento de Productividad</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="h2 gradient-text fw-bold mb-1">5000+</div>
                            <div class="text-muted">Granjas Transformadas</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="h2 gradient-text fw-bold mb-1">24/7</div>
                            <div class="text-muted">Monitoreo Inteligente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Elementos decorativos -->
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
            <div class="position-absolute" style="top: 10%; right: 10%; opacity: 0.1;">
                <i class="fas fa-egg" style="font-size: 8rem; color: var(--primary);"></i>
            </div>
            <div class="position-absolute floating-element" style="bottom: 20%; left: 5%; opacity: 0.1;">
                <i class="fas fa-feather-alt" style="font-size: 6rem; color: var(--secondary);"></i>
            </div>
        </div>
    </section>

    <!-- Sección de Tecnología Avanzada -->
    <section class="py-5" id="technology" style="background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="section-title text-start mb-4">
                        Tecnología de 
                        <span class="gradient-text">Vanguardia</span>
                    </h2>
                    <p class="section-subtitle text-start">
                        Nuestra plataforma integra las últimas innovaciones en inteligencia artificial, 
                        IoT y análisis de datos para transformar completamente tu operación avícola.
                    </p>
                    
                    <div class="row g-4 mt-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon pulse-glow" style="width: 60px; height: 60px; margin-bottom: 0;">
                                        <i class="fas fa-brain" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">IA Predictiva</h5>
                                    <p class="text-muted mb-0">Predicción de enfermedades y optimización de producción</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon pulse-glow" style="width: 60px; height: 60px; margin-bottom: 0;">
                                        <i class="fas fa-wifi" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">IoT Integrado</h5>
                                    <p class="text-muted mb-0">Sensores inteligentes para monitoreo 24/7</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon pulse-glow" style="width: 60px; height: 60px; margin-bottom: 0;">
                                        <i class="fas fa-mobile-alt" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">App Móvil</h5>
                                    <p class="text-muted mb-0">Control total desde cualquier lugar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon pulse-glow" style="width: 60px; height: 60px; margin-bottom: 0;">
                                        <i class="fas fa-cloud" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">Cloud Seguro</h5>
                                    <p class="text-muted mb-0">Datos protegidos y siempre disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="position-relative">
                        <!-- Dashboard Mockup Interactivo -->
                        <div class="dashboard-mockup glass-effect rounded-4 p-4 shadow-lg">
                            <div class="mockup-header d-flex align-items-center mb-3">
                                <div class="d-flex gap-2">
                                    <div class="mockup-dot bg-danger rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div class="mockup-dot bg-warning rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div class="mockup-dot bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                                </div>
                                <div class="ms-3 text-muted small">Avicontrol2025 Dashboard</div>
                            </div>
                            
                            <div class="mockup-content">
                                <!-- Gráfico animado -->
                                <div class="animated-chart mb-3">
                                    <div class="chart-title text-dark fw-bold mb-2">Producción en Tiempo Real</div>
                                    <div class="chart-bars d-flex align-items-end gap-2" style="height: 120px;">
                                        <div class="chart-bar bg-primary rounded-top animated-bar" style="height: 70%; animation-delay: 0s;"></div>
                                        <div class="chart-bar bg-success rounded-top animated-bar" style="height: 85%; animation-delay: 0.2s;"></div>
                                        <div class="chart-bar bg-warning rounded-top animated-bar" style="height: 60%; animation-delay: 0.4s;"></div>
                                        <div class="chart-bar bg-info rounded-top animated-bar" style="height: 95%; animation-delay: 0.6s;"></div>
                                        <div class="chart-bar bg-primary rounded-top animated-bar" style="height: 80%; animation-delay: 0.8s;"></div>
                                    </div>
                                </div>
                                
                                <!-- Métricas en vivo -->
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="metric-card bg-light rounded p-2 text-center">
                                            <div class="metric-value text-success fw-bold">98.5%</div>
                                            <div class="metric-label text-muted small">Eficiencia</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="metric-card bg-light rounded p-2 text-center">
                                            <div class="metric-value text-primary fw-bold">2,450</div>
                                            <div class="metric-label text-muted small">Huevos Hoy</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Elementos flotantes decorativos -->
                        <div class="position-absolute floating-element" style="top: -30px; right: -30px;">
                            <div class="bg-gradient-primary rounded-circle p-4 shadow-xl pulse-glow">
                                <i class="fas fa-crown text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        
                        <div class="position-absolute floating-element" style="bottom: -20px; left: -20px;">
                            <div class="bg-gradient-secondary rounded-circle p-3 shadow-lg">
                                <i class="fas fa-rocket text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Estadísticas en Tiempo Real -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
        <div class="container py-5">
            <div class="row align-items-center text-white">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="display-4 fw-bold mb-4">
                        Monitoreo <span class="text-warning">24/7</span>
                    </h2>
                    <p class="lead mb-4">
                        Nuestros sensores IoT recopilan datos en tiempo real de más de 5,000 granjas 
                        alrededor del mundo, proporcionando insights instantáneos para optimizar tu producción.
                    </p>
                    
                    <!-- Métricas en tiempo real -->
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="real-time-metric">
                                <div class="metric-icon mb-2">
                                    <i class="fas fa-egg text-warning" style="font-size: 2rem;"></i>
                                </div>
                                <div class="metric-number text-white fw-bold" style="font-size: 2rem;" data-target="2847">0</div>
                                <div class="metric-label text-light">Huevos Producidos Hoy</div>
                                <div class="metric-trend text-success">
                                    <i class="fas fa-arrow-up"></i> +12.5%
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="real-time-metric">
                                <div class="metric-icon mb-2">
                                    <i class="fas fa-thermometer-half text-info" style="font-size: 2rem;"></i>
                                </div>
                                <div class="metric-number text-white fw-bold" style="font-size: 2rem;" data-target="23">0</div>
                                <div class="metric-label text-light">Temperatura Promedio (°C)</div>
                                <div class="metric-trend text-success">
                                    <i class="fas fa-check-circle"></i> Óptima
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <!-- Simulador de datos en vivo -->
                    <div class="live-data-simulator glass-effect rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-white mb-0">
                                <i class="fas fa-broadcast-tower me-2"></i>
                                Datos en Vivo
                            </h5>
                            <div class="live-indicator">
                                <span class="live-dot"></span>
                                <span class="text-light small">EN VIVO</span>
                            </div>
                        </div>
                        
                        <!-- Mini gráfico animado -->
                        <div class="live-chart mb-3">
                            <div class="chart-line"></div>
                        </div>
                        
                        <!-- Alertas en tiempo real -->
                        <div class="live-alerts">
                            <div class="alert alert-success alert-sm mb-2 animate__animated animate__fadeInRight">
                                <i class="fas fa-check-circle me-2"></i>
                                Galpón 3: Producción óptima
                            </div>
                            <div class="alert alert-warning alert-sm mb-2 animate__animated animate__fadeInRight" style="animation-delay: 1s;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Galpón 7: Revisar ventilación
                            </div>
                            <div class="alert alert-info alert-sm mb-0 animate__animated animate__fadeInRight" style="animation-delay: 2s;">
                                <i class="fas fa-info-circle me-2"></i>
                                Sistema: Funcionando perfectamente
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section Ultra Moderna -->
    <section class="py-5" id="features">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">
                    Características <span class="gradient-text">Revolucionarias</span>
                </h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Descubre las funcionalidades que están transformando la industria avícola mundial
                </p>
            </div>
            
            <div class="row g-4" id="features-container">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <h4 class="feature-title">Gestión Inteligente de Galpones</h4>
                        <p class="feature-description">
                            Control automatizado de temperatura, humedad y ventilación. 
                            Monitoreo en tiempo real con alertas predictivas para mantener 
                            condiciones óptimas 24/7.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-egg"></i>
                        </div>
                        <h4 class="feature-title">Producción con IA</h4>
                        <p class="feature-description">
                            Análisis predictivo de postura, clasificación automática por calidad 
                            y peso. Algoritmos de machine learning para maximizar la producción.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 class="feature-title">Nutrición Optimizada</h4>
                        <p class="feature-description">
                            Formulación automática de raciones basada en análisis nutricional. 
                            Control de inventarios con predicción de demanda inteligente.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4 class="feature-title">Salud Preventiva</h4>
                        <p class="feature-description">
                            Detección temprana de enfermedades mediante sensores biométricos. 
                            Protocolos de vacunación automatizados y trazabilidad completa.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="feature-title">Analytics Avanzado</h4>
                        <p class="feature-description">
                            Dashboards interactivos con métricas en tiempo real. 
                            Reportes personalizados y análisis de rentabilidad por galpón.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon floating-element">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-title">Bioseguridad Total</h4>
                        <p class="feature-description">
                            Sistema de acceso biométrico, control de visitas y protocolos 
                            de desinfección. Cumplimiento automático de normativas sanitarias.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Testimonios Innovadora -->
    <section class="py-5" style="background: var(--gray-50);">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">
                    Lo que Dicen Nuestros <span class="gradient-text">Expertos</span>
                </h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Testimonios reales de productores avícolas que han transformado sus granjas
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <div class="avatar-circle bg-gradient-primary">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">Carlos Mendoza</h5>
                                <p class="text-muted mb-0">Granja La Esperanza</p>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p class="mb-0">"Avicontrol2025 revolucionó nuestra operación. Aumentamos la producción un 45% en solo 6 meses. La IA predictiva es increíble."</p>
                        </div>
                        <div class="testimonial-stats">
                            <div class="stat-item">
                                <span class="stat-number">+45%</span>
                                <span class="stat-label">Producción</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">6</span>
                                <span class="stat-label">Meses</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <div class="avatar-circle bg-gradient-secondary">
                                    <i class="fas fa-user-tie text-white"></i>
                                </div>
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">María González</h5>
                                <p class="text-muted mb-0">Avícola San José</p>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p class="mb-0">"El monitoreo 24/7 nos salvó de una crisis. El sistema detectó problemas antes que nosotros. ROI del 300% en el primer año."</p>
                        </div>
                        <div class="testimonial-stats">
                            <div class="stat-item">
                                <span class="stat-number">300%</span>
                                <span class="stat-label">ROI</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24/7</span>
                                <span class="stat-label">Monitoreo</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">
                                <div class="avatar-circle bg-gradient-accent">
                                    <i class="fas fa-user-graduate text-white"></i>
                                </div>
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">Dr. Roberto Silva</h5>
                                <p class="text-muted mb-0">Consultor Avícola</p>
                                <div class="testimonial-rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <p class="mb-0">"Como consultor, recomiendo Avicontrol2025 a todas mis granjas. La tecnología es de clase mundial y el soporte excepcional."</p>
                        </div>
                        <div class="testimonial-stats">
                            <div class="stat-item">
                                <span class="stat-number">50+</span>
                                <span class="stat-label">Granjas</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">100%</span>
                                <span class="stat-label">Satisfacción</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería Visual Innovadora -->
    <section class="gallery-section" id="gallery" style="background: linear-gradient(135deg, var(--gray-100) 0%, white 100%);">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">
                    Showcase <span class="gradient-text">Avícola</span>
                </h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Descubre las capacidades visuales de nuestro sistema de gestión
                </p>
            </div>
            
            <div class="row g-4">
                <!-- Producción de Huevos -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern eggs-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-egg"></i>
                            </div>
                            <div class="visual-counter">2,847</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Producción de Huevos</h5>
                            <p class="visual-description">Monitoreo en tiempo real de la producción diaria</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 85%"></div>
                            </div>
                            <span class="visual-percentage">85% Eficiencia</span>
                        </div>
                    </div>
                </div>
                
                <!-- Galpones Modernos -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern barn-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="visual-counter">12</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Galpones Inteligentes</h5>
                            <p class="visual-description">Control automatizado de ambiente y bioseguridad</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 92%"></div>
                            </div>
                            <span class="visual-percentage">92% Automatización</span>
                        </div>
                    </div>
                </div>
                
                <!-- Bienestar Animal -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern wellness-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div class="visual-counter">98%</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Bienestar Animal</h5>
                            <p class="visual-description">Monitoreo de salud y condiciones óptimas</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 98%"></div>
                            </div>
                            <span class="visual-percentage">98% Salud Óptima</span>
                        </div>
                    </div>
                </div>
                
                <!-- Clasificación Automática -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern sorting-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-sort-amount-up"></i>
                            </div>
                            <div class="visual-counter">AI</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Clasificación IA</h5>
                            <p class="visual-description">Selección automática por calidad y tamaño</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 96%"></div>
                            </div>
                            <span class="visual-percentage">96% Precisión</span>
                        </div>
                    </div>
                </div>
                
                <!-- Nutrición Avanzada -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="500">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern nutrition-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="visual-counter">24/7</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Nutrición Inteligente</h5>
                            <p class="visual-description">Formulación automática de dietas balanceadas</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 89%"></div>
                            </div>
                            <span class="visual-percentage">89% Optimización</span>
                        </div>
                    </div>
                </div>
                
                <!-- Automatización -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="600">
                    <div class="visual-card">
                        <div class="visual-icon-container">
                            <div class="visual-bg-pattern tech-pattern"></div>
                            <div class="visual-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="visual-counter">IoT</div>
                        </div>
                        <div class="visual-content">
                            <h5 class="visual-title">Automatización Total</h5>
                            <p class="visual-description">Integración IoT para control remoto completo</p>
                            <div class="visual-progress">
                                <div class="progress-bar" style="width: 94%"></div>
                            </div>
                            <span class="visual-percentage">94% Conectividad</span>
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
        // Manejo de errores global
        window.addEventListener('error', function(e) {
            console.log('Error capturado:', e.error);
        });
        
        // Timeout de seguridad para forzar la carga
        setTimeout(function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                console.log('Forzando carga de página por timeout');
                preloader.style.display = 'none';
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }
        }, 3000);
        
        // Timeout adicional más corto
        setTimeout(function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 1000);
        
        // Preloader simplificado
        window.addEventListener('load', function() {
            try {
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    preloader.classList.add('fade-out');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }
            } catch (error) {
                console.log('Error en preloader:', error);
                // Forzar que la página se muestre
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    preloader.style.display = 'none';
                }
            }
        });
        
        // Inicializar AOS de forma segura
        try {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    offset: 50,
                    disable: 'mobile'
                });
            }
        } catch (error) {
            console.log('Error inicializando AOS:', error);
        }
        
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
        
        // Alerta de bienvenida simplificada
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¡Bienvenido a Avicontrol2025!',
                        text: 'El sistema integral de gestión avícola más avanzado',
                        icon: 'success',
                        confirmButtonText: 'Comenzar',
                        confirmButtonColor: '#059669',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }, 1500);
        });
        
        // Back to top button
        const backToTop = document.querySelector('.back-to-top');
        if (backToTop) {
            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        
        // Animaciones adicionales simples
        function initAnimations() {
            // Animaciones básicas para elementos que aparecen
            const elementsToAnimate = document.querySelectorAll('.feature-card, .hero-content');
            elementsToAnimate.forEach((element, index) => {
                if (element) {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(30px)';
                    
                    setTimeout(() => {
                        element.style.transition = 'all 0.6s ease';
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }, index * 200);
                }
            });
        }
        
        // Inicializar animaciones cuando la página esté lista
        setTimeout(initAnimations, 500);
        
        // Contador animado para métricas en tiempo real
        function animateCounters() {
            const counters = document.querySelectorAll('.metric-number[data-target]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // 2 segundos
                const increment = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 16);
            });
        }
        
        // Inicializar contadores cuando sean visibles
        const metricsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    metricsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        setTimeout(() => {
            const metricsSection = document.querySelector('.real-time-metric');
            if (metricsSection) {
                metricsObserver.observe(metricsSection);
            }
        }, 1000);
        
        // Simulador de actualizaciones en tiempo real
        function simulateLiveUpdates() {
            const alerts = document.querySelectorAll('.live-alerts .alert');
            let currentAlert = 0;
            
            setInterval(() => {
                if (alerts.length > 0) {
                    // Remover clase de animación anterior
                    alerts.forEach(alert => {
                        alert.classList.remove('animate__fadeInRight', 'animate__fadeOutRight');
                    });
                    
                    // Animar salida del alert actual
                    if (alerts[currentAlert]) {
                        alerts[currentAlert].classList.add('animate__fadeOutRight');
                    }
                    
                    // Cambiar al siguiente alert
                    setTimeout(() => {
                        currentAlert = (currentAlert + 1) % alerts.length;
                        if (alerts[currentAlert]) {
                            alerts[currentAlert].classList.add('animate__fadeInRight');
                        }
                    }, 500);
                }
            }, 4000); // Cambiar cada 4 segundos
        }
        
        // Inicializar simulador de updates
        setTimeout(simulateLiveUpdates, 3000);
        
        // Animar barras de progreso de la galería visual
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-bar');
            
            progressBars.forEach((bar, index) => {
                const width = bar.style.width;
                bar.style.width = '0%';
                
                setTimeout(() => {
                    bar.style.width = width;
                }, index * 200);
            });
        }
        
        // Observer para barras de progreso
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateProgressBars();
                    progressObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        
        setTimeout(() => {
            const gallerySection = document.querySelector('#gallery');
            if (gallerySection) {
                progressObserver.observe(gallerySection);
            }
        }, 1000);
        
        // Efectos adicionales para la galería visual
        function initVisualEffects() {
            const visualCards = document.querySelectorAll('.visual-card');
            
            visualCards.forEach((card, index) => {
                // Efecto de aparición escalonada
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
                
                // Efecto hover mejorado
                card.addEventListener('mouseenter', () => {
                    const icon = card.querySelector('.visual-icon');
                    if (icon) {
                        icon.style.transform = 'scale(1.1) rotate(10deg)';
                    }
                });
                
                card.addEventListener('mouseleave', () => {
                    const icon = card.querySelector('.visual-icon');
                    if (icon) {
                        icon.style.transform = 'scale(1) rotate(0deg)';
                    }
                });
            });
        }
        
        // Inicializar efectos visuales
        setTimeout(initVisualEffects, 2000);
        
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
        const currentYearElement = document.getElementById('current-year');
        if (currentYearElement) {
            currentYearElement.textContent = new Date().getFullYear();
        }
        
        // Efectos simples y seguros
        
        // Parallax suave para elementos floating (simplificado)
        let ticking = false;
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.floating-element');
            
            parallaxElements.forEach(element => {
                if (element) {
                    const speed = 0.3;
                    const yPos = -(scrolled * speed);
                    element.style.transform = `translateY(${yPos}px)`;
                }
            });
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });
        
        // Intersection Observer simplificado
        if (window.IntersectionObserver) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observar elementos de forma segura
            setTimeout(() => {
                document.querySelectorAll('.feature-card').forEach(el => {
                    if (el) observer.observe(el);
                });
            }, 500);
        }
        
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
