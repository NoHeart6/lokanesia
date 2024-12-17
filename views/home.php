<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokanesia - Jelajahi Keindahan Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #ff6b6b;
            --dark-color: #1a1a1a;
            --light-color: #f8f9fa;
            --gradient-1: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --gradient-2: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            --gradient-3: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background-color: var(--light-color);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }

        .text-gradient {
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Navbar Styles */
        .navbar-float {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 15px 30px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-float.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            margin: 0 15px;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--accent-color) !important;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: var(--gradient-1);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1592364395653-83e648b20cc2?auto=format&fit=crop&w=1920&q=80') center/cover;
            opacity: 0.1;
            animation: zoomBg 20s infinite alternate;
        }

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* Search Box */
        .search-float {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1000px;
            z-index: 100;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .search-tab {
            padding: 12px 25px;
            border: none;
            background: none;
            color: #666;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .search-tab:hover {
            color: var(--primary-color);
        }

        .search-tab.active {
            color: var(--primary-color);
            background: rgba(30, 60, 114, 0.1);
            border-radius: 25px;
        }

        /* Destination Cards */
        .destination-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 30px;
        }

        .destination-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
            cursor: pointer;
        }

        .destination-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .destination-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .destination-card:hover .destination-img {
            transform: scale(1.1);
        }

        .destination-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            transform: translateY(100px);
            transition: all 0.5s ease;
        }

        .destination-card:hover .destination-overlay {
            transform: translateY(0);
        }

        /* Virtual Tour Section */
        .virtual-tour {
            background: white;
            padding: 100px 0;
            position: relative;
        }

        .section-title {
            font-size: 3rem;
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .tour-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.5s ease;
        }

        .tour-card::before {
            content: '360°';
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.9);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            z-index: 1;
        }

        /* Events Section */
        .events-section {
            background: var(--light-color);
            padding: 100px 0;
        }

        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.5s ease;
        }

        .event-date {
            background: var(--gradient-2);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .event-date .day {
            font-size: 2rem;
            font-weight: bold;
        }

        /* Tips Section */
        .tips-section {
            background: var(--dark-color);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .tips-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('path/to/pattern.png');
            opacity: 0.1;
        }

        .tip-card {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        /* Custom Button */
        .btn-custom {
            background: var(--gradient-2);
            color: white !important;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        /* Weather Widget */
        .weather-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .weather-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }

        /* New Layout Styles */
        .diagonal-section {
            position: relative;
            padding: 150px 0;
            margin: 100px 0;
            background: var(--gradient-1);
            transform: skewY(-5deg);
        }

        .diagonal-section > * {
            transform: skewY(5deg);
        }

        .parallax-section {
            position: relative;
            min-height: 500px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .parallax-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }

        .stats-counter {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stats-counter:hover {
            transform: translateY(-10px);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 30px 0;
        }

        .category-card {
            position: relative;
            height: 300px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
        }

        .featured-experience {
            position: relative;
            padding: 100px 0;
            background: var(--light-color);
        }

        .experience-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 15px;
        }

        .experience-image {
            height: 250px;
            overflow: hidden;
        }

        .experience-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .experience-content {
            padding: 30px;
        }

        .experience-price {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent-color);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
        }

        .testimonial-section {
            position: relative;
            padding: 100px 0;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            overflow: hidden;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }

        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.6;
            color: white;
            margin-bottom: 25px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .author-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .testimonial-rating {
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .testimonial-stat {
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .testimonial-stat:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .testimonial-stat h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        /* Logo styles */
        .navbar-brand img {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .cta-section {
            position: relative;
            padding: 150px 0;
            background: url('https://source.unsplash.com/random/1920x1080?indonesia-landscape') center/cover;
            color: white;
            text-align: center;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
        }

        .instagram-feed {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 30px 0;
        }

        .instagram-item {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 15px;
        }

        .instagram-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .instagram-item:hover img {
            transform: scale(1.1);
        }

        .footer {
            background: var(--gradient-1);
            color: white;
            padding: 80px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1592364395653-83e648b20cc2?auto=format&fit=crop&w=1920&q=80') center/cover;
            opacity: 0.05;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-description {
            opacity: 0.8;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .footer-heading {
            font-size: 1.2rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--accent-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            opacity: 1;
            padding-left: 10px;
            color: var(--accent-color);
        }

        .footer-social {
            margin-top: 30px;
        }

        .footer-social a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            opacity: 0.8;
        }

        /* Adventure Section Styles */
        .adventure-section {
            position: relative;
            padding: 150px 0;
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                url('https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1920&q=80');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        .adventure-content {
            position: relative;
            z-index: 2;
        }

        .adventure-title {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: titleGlow 2s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% {
                text-shadow: 0 0 20px rgba(255,215,0,0.5);
            }
            50% {
                text-shadow: 0 0 40px rgba(255,215,0,0.8);
            }
        }

        .adventure-description {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.5rem;
            color: #ffffff;
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: 0.5s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .adventure-btn {
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #fff;
            background: linear-gradient(45deg, #FF4E50, #F9D423);
            border: none;
            border-radius: 50px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .adventure-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .adventure-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .adventure-btn:hover::after {
            animation: btnShine 1.5s ease-out;
        }

        @keyframes btnShine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                transform: translate(100vw, -100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Menghapus animasi float dari lottie-container */
        .lottie-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            /* Menghapus animasi float */
        }

        .developer-section {
            position: relative;
            background: #f8f9fa;
            padding: 100px 0;
            overflow: hidden;
        }

        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            opacity: 0.05;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .dev-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(45deg, #FF6B6B, #FFE66D);
            color: white;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
        }

        .gradient-text {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .title-underline {
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #FF6B6B, #FFE66D);
            margin: 20px auto;
            border-radius: 2px;
        }

        .developer-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .developer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }

        .card-inner {
            display: flex;
            padding: 40px;
        }

        .profile-side {
            flex: 0 0 300px;
            padding-right: 40px;
            border-right: 2px solid rgba(0,0,0,0.05);
        }

        .profile-image-container {
            position: relative;
            margin-bottom: 30px;
        }

        .profile-image-wrapper {
            width: 250px;
            height: 250px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .profile-frame {
            position: absolute;
            top: 20px;
            left: 20px;
            right: -20px;
            bottom: -20px;
            border: 3px solid #FF6B6B;
            border-radius: 20px;
            z-index: 1;
        }

        .developer-card:hover .profile-image {
            transform: scale(1.05);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e3c72;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .social-icon:hover {
            background: #1e3c72;
            color: white;
            transform: translateY(-5px);
        }

        .social-icon[data-tooltip]:before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            font-size: 0.8rem;
            border-radius: 5px;
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .social-icon:hover[data-tooltip]:before {
            opacity: 1;
            transform: translateX(-50%) translateY(-5px);
        }

        .info-side {
            flex: 1;
            padding-left: 40px;
        }

        .developer-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .developer-role {
            color: #FF6B6B;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .developer-nim {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .dev-details {
            margin: 20px 0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
        }

        .detail-item i {
            width: 25px;
            color: #1e3c72;
            margin-right: 10px;
        }

        .skills-section {
            margin: 30px 0;
        }

        .skills-section h4 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .skill-tag {
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 15px;
            color: #1e3c72;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .skill-tag:hover {
            background: #1e3c72;
            color: white;
            transform: translateY(-2px);
        }

        .quote-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            position: relative;
        }

        .quote-icon {
            color: #FF6B6B;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .dev-quote {
            font-style: italic;
            color: #666;
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        @media (max-width: 992px) {
            .card-inner {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-side {
                border-right: none;
                border-bottom: 2px solid rgba(0,0,0,0.05);
                padding-right: 0;
                padding-bottom: 40px;
                margin-bottom: 40px;
            }

            .info-side {
                padding-left: 0;
            }

            .detail-item {
                justify-content: center;
            }

            .skill-tags {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .profile-image-wrapper {
                width: 200px;
                height: 200px;
            }

            .developer-name {
                font-size: 2rem;
            }

            .gradient-text {
                font-size: 2.5rem;
            }
        }

        /* Tambahan CSS untuk smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div id="preloaderAnimation"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar-float">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#home">
                <img src="https://i.pinimg.com/736x/66/dd/d0/66ddd0a43433943549bd2beb9cec5273.jpg" alt="Lokanesia" height="50" style="object-fit: contain;">
            </a>
            <div class="d-none d-md-flex">
                <a class="nav-link" href="#home">Beranda</a>
                <a class="nav-link" href="#destinasi">Destinasi</a>
                <a class="nav-link" href="#virtual-tour">Virtual Tour</a>
                <a class="nav-link" href="#events">Event</a>
                <a class="nav-link" href="#tips">Tips</a>
            </div>
            <a href="/login" class="btn-custom">Masuk</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <!-- Particle Animation -->
        <div class="hero-particles" id="particles"></div>
        
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">Temukan Keajaiban <span class="text-gradient">Indonesia</span></h1>
                    <p class="lead mb-4">Jelajahi ribuan destinasi wisata menakjubkan, dari pantai eksotis hingga gunung mempesona. Mulai petualangan Anda bersama Lokanesia!</p>
                    
                    <!-- Category Pills -->
                    <div class="d-flex flex-wrap mb-4">
                        <span class="category-pill">🏖️ Pantai Eksotis</span>
                        <span class="category-pill">🗻 Gunung Megah</span>
                        <span class="category-pill">🏛️ Warisan Sejarah</span>
                        <span class="category-pill">🍽️ Wisata Kuliner</span>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lottie-container" id="heroAnimation"></div>
                </div>
            </div>
        </div>

        <!-- Floating Search Box -->
        <div class="search-float">
            <div class="search-box">
                <div class="search-tabs d-flex justify-content-center mb-4">
                    <button class="search-tab active">🔍 Destinasi</button>
                    <button class="search-tab">🏨 Penginapan</button>
                    <button class="search-tab">🚗 Transport</button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="destination" placeholder="Destinasi">
                            <label for="destination">Kemana tujuan Anda?</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="date">
                            <label for="date">Tanggal Kunjungan</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-control" id="people">
                                <option>1-2 Orang</option>
                                <option>3-5 Orang</option>
                                <option>6+ Orang</option>
                            </select>
                            <label for="people">Jumlah Orang</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-custom w-100 h-100">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3" data-aos="fade-up">
                    <div class="stats-counter">
                        <div class="stats-number" data-count="1000">0</div>
                        <div class="stats-label">Destinasi Wisata</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-counter">
                        <div class="stats-number" data-count="500">0</div>
                        <div class="stats-label">Hotel Partner</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-counter">
                        <div class="stats-number" data-count="100000">0</div>
                        <div class="stats-label">Traveler Puas</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-counter">
                        <div class="stats-number" data-count="34">0</div>
                        <div class="stats-label">Provinsi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="diagonal-section">
        <div class="container">
            <h2 class="section-title text-center text-white" data-aos="fade-up">Jelajahi Kategori</h2>
            <p class="text-center text-white mb-5" data-aos="fade-up" data-aos-delay="100">Temukan berbagai jenis wisata yang sesuai dengan minat Anda</p>
            
            <div class="category-grid">
                <div class="category-card" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1537956965359-7573183d1f57?auto=format&fit=crop&w=800&q=80" alt="Pantai">
                    <div class="category-overlay">
                        <h4>Wisata Pantai</h4>
                        <p>200+ Destinasi</p>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://images.unsplash.com/photo-1512291313931-d4291048e7b6?auto=format&fit=crop&w=800&q=80" alt="Gunung">
                    <div class="category-overlay">
                        <h4>Wisata Gunung</h4>
                        <p>150+ Destinasi</p>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="https://images.unsplash.com/photo-1592364395653-83e648b20cc2?auto=format&fit=crop&w=800&q=80" alt="Sejarah">
                    <div class="category-overlay">
                        <h4>Wisata Sejarah</h4>
                        <p>100+ Destinasi</p>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=800&q=80" alt="Kuliner">
                    <div class="category-overlay">
                        <h4>Wisata Kuliner</h4>
                        <p>300+ Destinasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Experience -->
    <section class="featured-experience">
        <div class="container">
            <h2 class="section-title text-center" data-aos="fade-up">Pengalaman Terbaik</h2>
            <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Nikmati pengalaman wisata yang tak terlupakan</p>
            
            <div class="row">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="experience-card">
                        <div class="experience-image">
                            <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80" alt="Diving Raja Ampat">
                        </div>
                        <div class="experience-content">
                            <h5>Diving di Raja Ampat</h5>
                            <p>Jelajahi keindahan bawah laut dengan instruktur profesional</p>
                            <div class="experience-price">Rp 2.500.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="experience-card">
                        <div class="experience-image">
                            <img src="https://th.bing.com/th/id/R.47b41458ad625491ef03f500f6dd9a03?rik=fmU342pzGVhDng&riu=http%3a%2f%2fs3.weddbook.com%2ft4%2f2%2f0%2f6%2f2067929%2fmount-bromo-java-indonesia-bucket-listplaces-to-see-pinterest.jpg&ehk=CC4jkPV5VbSmgxmiKwL37CW9qlNrNtEDzaYtqtxmjBA%3d&risl=&pid=ImgRaw&r=0" alt="Sunrise Bromo">
                        </div>
                        <div class="experience-content">
                            <h5>Sunrise di Gunung Bromo</h5>
                            <p>Saksikan matahari terbit yang memukau dari ketinggian</p>
                            <div class="experience-price">Rp 1.800.000</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="experience-card">
                        <div class="experience-image">
                            <img src="https://th.bing.com/th/id/OIP.O-D-NR8OHV4M8_z0crUqcQHaEK?rs=1&pid=ImgDetMain" alt="Borobudur Temple">
                        </div>
                        <div class="experience-content">
                            <h5>Private Tour Borobudur</h5>
                            <p>Eksplorasi candi terbesar di dunia dengan guide profesional</p>
                            <div class="experience-price">Rp 1.200.000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Parallax Section -->
    <section class="adventure-section">
        <div class="floating-elements">
            <div class="floating-element" style="top: 20%; left: 10%;"></div>
            <div class="floating-element" style="top: 60%; left: 20%;"></div>
            <div class="floating-element" style="top: 30%; left: 80%;"></div>
            <div class="floating-element" style="top: 70%; left: 60%;"></div>
            <div class="floating-element" style="top: 40%; left: 40%;"></div>
        </div>
        <div class="container">
            <div class="adventure-content text-center">
                <h2 class="adventure-title" data-aos="zoom-in">Mulai Petualangan Anda</h2>
                <p class="adventure-description" data-aos="fade-up" data-aos-delay="200">
                    Jelajahi keajaiban Indonesia yang belum pernah Anda temukan sebelumnya. 
                    Dari puncak gunung hingga kedalaman laut, setiap perjalanan adalah cerita baru yang menanti untuk ditulis.
                </p>
                <a href="http://localhost:8000/register" class="adventure-btn" data-aos="fade-up" data-aos-delay="400" style="text-decoration: none; display: inline-block;">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonial Section dengan layout baru -->
    <section class="testimonial-section">
        <div class="container">
            <h2 class="section-title text-center text-white mb-5" data-aos="fade-up">Apa Kata Mereka?</h2>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Pengalaman yang luar biasa! Lokanesia membantu saya menemukan tempat-tempat tersembunyi yang menakjubkan. Pelayanan guide sangat profesional dan informatif."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Author" class="author-image">
                            <div>
                                <h6 class="mb-0">Budi Santoso</h6>
                                <small>Adventure Traveler | Jakarta</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Website yang sangat membantu untuk merencanakan liburan. Informasi lengkap, booking mudah, dan harga transparan. Recommended banget!"</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Author" class="author-image">
                            <div>
                                <h6 class="mb-0">Sarah Amalia</h6>
                                <small>Food Blogger | Bandung</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Sebagai fotografer, saya sangat terkesan dengan rekomendasi spot foto yang diberikan. Setiap destinasi punya keunikan tersendiri!"</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Author" class="author-image">
                            <div>
                                <h6 class="mb-0">Reza Pratama</h6>
                                <small>Travel Photographer | Yogyakarta</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial Stats -->
            <div class="row mt-5 text-center">
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up">
                    <div class="testimonial-stat">
                        <h3 class="text-warning mb-2">98%</h3>
                        <p class="text-white-50 mb-0">Kepuasan Pelanggan</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-stat">
                        <h3 class="text-warning mb-2">10K+</h3>
                        <p class="text-white-50 mb-0">Review Positif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-stat">
                        <h3 class="text-warning mb-2">500+</h3>
                        <p class="text-white-50 mb-0">Guide Profesional</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-stat">
                        <h3 class="text-warning mb-2">24/7</h3>
                        <p class="text-white-50 mb-0">Dukungan Pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Developer Info Section -->
    <section class="developer-section py-5">
        <div class="animated-bg"></div>
        <div class="container">
            <div class="section-header text-center mb-5">
                <span class="dev-badge" data-aos="fade-down">Web Developer</span>
                <h2 class="section-title gradient-text" data-aos="fade-up">Informasi Pembuat</h2>
                <div class="title-underline"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="developer-card" data-aos="zoom-in">
                        <div class="card-inner">
                            <div class="profile-side">
                                <div class="profile-image-container">
                                    <div class="profile-image-wrapper">
                                        <img src="https://i.pinimg.com/736x/8f/56/c9/8f56c952730fd9885975fcae6fae1692.jpg" 
                                             alt="Astrit Dwi Antika" 
                                             class="profile-image">
                                    </div>
                                    <div class="profile-frame"></div>
                                </div>
                                <div class="social-links">
                                    <a href="#" class="social-icon" data-tooltip="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="#" class="social-icon" data-tooltip="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" class="social-icon" data-tooltip="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="info-side">
                                <div class="dev-info">
                                    <h3 class="developer-name">Astrit Dwi Antika</h3>
                                    <div class="developer-role">Full Stack Developer</div>
                                    <div class="developer-nim">NIM: 231240001401</div>
                                    <div class="dev-details">
                                        <div class="detail-item">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span>Teknik Informatika</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-university"></i>
                                            <span>Universitas nahdlatul ulama jepara</span>
                                        </div>
                                    </div>
                                    <div class="skills-section">
                                        <h4>Skills</h4>
                                        <div class="skill-tags">
                                            <span class="skill-tag">HTML5</span>
                                            <span class="skill-tag">CSS3</span>
                                            <span class="skill-tag">JavaScript</span>
                                            <span class="skill-tag">PHP</span>
                                            <span class="skill-tag">MySQL</span>
                                        </div>
                                    </div>
                                    <div class="quote-section">
                                        <i class="fas fa-quote-left quote-icon"></i>
                                        <p class="dev-quote">"Turning ideas into reality through elegant code and creative solutions."</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Kolom Brand -->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">Lokanesia</div>
                    <p class="footer-description">
                        Jelajahi keindahan Indonesia bersama Lokanesia. Temukan destinasi wisata lokal terbaik dengan pengalaman yang tak terlupakan.
                    </p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Kolom Destinasi -->
                <div class="col-lg-2 col-md-6 mb-5 mb-lg-0">
                    <h4 class="footer-heading">Destinasi</h4>
                    <ul class="footer-links">
                        <li><a href="#">Pantai</a></li>
                        <li><a href="#">Pegunungan</a></li>
                        <li><a href="#">Budaya</a></li>
                        <li><a href="#">Kuliner</a></li>
                        <li><a href="#">Sejarah</a></li>
                    </ul>
                </div>

                <!-- Kolom Informasi -->
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <h4 class="footer-heading">Informasi</h4>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Cara Pemesanan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <!-- Kolom Kontak -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-heading">Hubungi Kami</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Lokanesia No. 123, Jakarta</li>
                        <li><i class="fas fa-phone me-2"></i> +62 123 4567 890</li>
                        <li><i class="fas fa-envelope me-2"></i> info@lokanesia.com</li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="mb-0">&copy; 2024 Lokanesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Preloader Animation
        const preloaderAnimation = lottie.loadAnimation({
            container: document.getElementById('preloaderAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets5.lottiefiles.com/packages/lf20_rwq6ciql.json' // Loading animation
        });

        // Hero Animation
        const heroAnimation = lottie.loadAnimation({
            container: document.getElementById('heroAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets7.lottiefiles.com/packages/lf20_oyi9a28g.json' // Travel/Adventure themed animation
        });

        // Weather Animation
        const weatherAnimation = lottie.loadAnimation({
            container: document.getElementById('weatherAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets9.lottiefiles.com/packages/lf20_KUFdS6.json' // Weather animation
        });

        // Particle Animation
        function createParticles() {
            const container = document.getElementById('particles');
            for(let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                container.appendChild(particle);
            }
        }
        createParticles();

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-float');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Remove Preloader
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.querySelector('.preloader').style.display = 'none';
            }, 2000);
        });

        // Search Tabs
        const searchTabs = document.querySelectorAll('.search-tab');
        searchTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                searchTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });

        // Stats Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.dataset.count);
            let count = 0;
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps

            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(count).toLocaleString();
                }
            }, 16);
        }

        // Trigger counter animation when in viewport
        const counters = document.querySelectorAll('.stats-number');
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        counters.forEach(counter => {
            observer.observe(counter);
        });

        // Tambahkan script untuk floating elements
        function createFloatingElements() {
            const container = document.querySelector('.floating-elements');
            for(let i = 0; i < 10; i++) {
                const element = document.createElement('div');
                element.className = 'floating-element';
                element.style.top = Math.random() * 100 + '%';
                element.style.left = Math.random() * 100 + '%';
                element.style.animationDelay = (Math.random() * 5) + 's';
                element.style.animationDuration = (10 + Math.random() * 20) + 's';
                container.appendChild(element);
            }
        }

        // Panggil fungsi saat halaman dimuat
        window.addEventListener('load', createFloatingElements);

        // Smooth scroll untuk semua link navbar
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Offset untuk navbar
                    const navbarHeight = document.querySelector('.navbar-float').offsetHeight;
                    const targetPosition = targetSection.offsetTop - navbarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Highlight active section saat scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            const scrollPosition = window.scrollY + 100; // offset untuk navbar

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${sectionId}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });

        // Fungsi untuk tombol pencarian
        document.querySelector('.search-box .btn-custom').addEventListener('click', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destination').value;
            const date = document.getElementById('date').value;
            const people = document.getElementById('people').value;

            // Redirect to login page
            window.location.href = '/login';
        });

        // Fungsi untuk tombol kategori
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const category = this.querySelector('h4').textContent;
                // Di sini bisa ditambahkan logika navigasi ke halaman kategori
                console.log('Membuka kategori:', category);
            });
        });

        // Fungsi untuk tombol experience
        document.querySelectorAll('.experience-card').forEach(card => {
            card.addEventListener('click', function() {
                const title = this.querySelector('h5').textContent;
                // Di sini bisa ditambahkan logika navigasi ke halaman detail experience
                console.log('Membuka experience:', title);
            });
        });

        // Fungsi untuk tombol "Mulai Sekarang"
        document.querySelector('.adventure-btn').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'http://localhost:8000/register';
        });
    </script>
</body>
</html>