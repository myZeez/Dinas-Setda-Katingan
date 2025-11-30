<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ \App\Helpers\SettingHelper::description() }}">
    <title>@yield('title', 'Beranda') - {{ \App\Helpers\SettingHelper::siteName() }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ \App\Helpers\SettingHelper::favicon() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #dc2626;
            --primary-dark: #b91c1c;
            --secondary-color: #1e293b;
            --accent-color: #fbbf24;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ============================================
           TOP NAVBAR
        ============================================ */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 12px 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 8px 0;
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 42px;
            width: auto;
        }

        .navbar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .navbar-brand-text h1 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
        }

        .navbar-brand-text span {
            font-size: 11px;
            color: var(--text-secondary);
        }

        /* Desktop Nav Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(220, 38, 38, 0.05);
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        /* Dropdown Arrow */
        .nav-link .arrow {
            border: solid currentColor;
            border-width: 0 1.5px 1.5px 0;
            padding: 2.5px;
            transform: rotate(45deg);
            transition: transform 0.2s ease;
            margin-left: 2px;
        }

        .nav-item:hover .nav-link .arrow,
        .nav-item.open .nav-link .arrow {
            transform: rotate(-135deg);
        }

        /* Dropdown Menu */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 220px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
            z-index: 100;
        }

        .nav-item:hover .nav-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .nav-dropdown a {
            display: block;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-dropdown a:hover {
            background: rgba(220, 38, 38, 0.05);
            color: var(--primary-color);
        }

        /* Contact Button */
        .nav-btn {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            background: var(--primary-color);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: 8px;
        }

        .nav-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* Hide desktop menu on mobile */
        @media (max-width: 991px) {
            .navbar-menu {
                display: none;
            }
            .navbar-brand-text {
                display: none;
            }
        }

        /* ============================================
           BOTTOM NAVIGATION (Mobile Only)
        ============================================ */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-top: 1px solid #f1f5f9;
            padding: 6px 0;
            padding-bottom: calc(6px + env(safe-area-inset-bottom));
        }

        .bottom-nav-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            max-width: 500px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #94a3b8;
            padding: 6px 10px;
            border-radius: 10px;
            transition: all 0.2s ease;
            min-width: 56px;
        }

        .bottom-nav-item i {
            font-size: 20px;
            margin-bottom: 2px;
        }

        .bottom-nav-item span {
            font-size: 10px;
            font-weight: 500;
        }

        .bottom-nav-item:hover,
        .bottom-nav-item.active {
            color: var(--primary-color);
        }

        .bottom-nav-item.active {
            background: rgba(220, 38, 38, 0.08);
        }

        /* Center Button - Portal Layanan */
        .bottom-nav-center {
            position: relative;
            margin-top: -20px;
        }

        .bottom-nav-center a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 16px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            transition: all 0.3s ease;
        }

        .bottom-nav-center a:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
        }

        .bottom-nav-center a i {
            font-size: 22px;
        }

        .bottom-nav-center a span {
            font-size: 8px;
            font-weight: 600;
            margin-top: 1px;
        }

        /* More Button with Dropdown */
        .bottom-nav-more {
            position: relative;
            cursor: pointer;
        }

        .bottom-more-dropdown {
            position: fixed;
            bottom: 80px;
            right: 20px;
            min-width: 180px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 -4px 30px rgba(0,0,0,0.15);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
            z-index: 1001;
        }

        .bottom-more-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .bottom-more-dropdown::after {
            content: '';
            position: absolute;
            bottom: -6px;
            right: 24px;
            width: 12px;
            height: 12px;
            background: white;
            transform: rotate(45deg);
        }

        .bottom-more-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .bottom-more-dropdown a:hover {
            background: rgba(220, 38, 38, 0.05);
            color: var(--primary-color);
        }

        .bottom-more-dropdown a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: var(--text-secondary);
        }

        .bottom-more-dropdown a:hover i {
            color: var(--primary-color);
        }

        .bottom-dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        /* Overlay */
        .bottom-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .bottom-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Show bottom nav on mobile */
        @media (max-width: 991px) {
            .bottom-nav {
                display: block;
            }

            body {
                padding-bottom: 70px;
            }
        }

        @media (max-width: 380px) {
            .bottom-nav-item {
                min-width: 48px;
                padding: 6px 6px;
            }

            .bottom-nav-item i {
                font-size: 18px;
            }

            .bottom-nav-item span {
                font-size: 9px;
            }

            .bottom-nav-center a {
                width: 50px;
                height: 50px;
            }
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding-top: 70px;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 991px) {
            .hero-section {
                padding-top: 60px;
            }
        }

        .hero-content {
            padding: 40px 0 40px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: var(--bg-light);
            border-radius: 50px;
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--text-primary);
        }

        .hero-title .highlight {
            color: var(--primary-color);
            font-style: italic;
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-hero {
            background: var(--primary-color);
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-hero:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.35);
        }

        .btn-secondary-hero {
            background: transparent;
            color: var(--text-primary);
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary-hero:hover {
            border-color: var(--text-primary);
            color: var(--text-primary);
        }

        /* Partner Logos */
        .partner-logos {
            padding: 40px 0;
            border-top: 1px solid var(--border-color);
            margin-top: 60px;
        }

        .partner-logos img {
            height: 30px;
            opacity: 0.5;
            filter: grayscale(100%);
            transition: all 0.3s ease;
        }

        .partner-logos img:hover {
            opacity: 1;
            filter: grayscale(0%);
        }

        /* Hero Image/Video */
        .hero-media {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0,0,0,0.15);
        }

        .hero-media img, .hero-media video {
            width: 100%;
            display: block;
        }

        .hero-media .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(220, 38, 38, 0.4);
        }

        .hero-media .play-button:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }

        /* Stacked Carousel */
        .stacked-carousel-wrapper {
            margin-top: 50px;
            padding: 100px 20px 120px;
        }

        .stacked-carousel {
            position: relative;
            height: 450px;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            perspective: 1200px;
        }

        .stacked-slide {
            position: absolute;
            left: 50%;
            width: 75%;
            max-width: 1100px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0,0,0,0.2);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center center;
            cursor: pointer;
        }

        .stacked-slide img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
        }

        /* Position states for stacked slides */
        .stacked-slide[data-position="prev-2"] {
            transform: translateX(-50%) translateY(-80px) scale(0.75);
            opacity: 0.2;
            z-index: 1;
        }

        .stacked-slide[data-position="prev-1"] {
            transform: translateX(-50%) translateY(-45px) scale(0.88);
            opacity: 0.35;
            z-index: 2;
        }

        .stacked-slide[data-position="active"],
        .stacked-slide.active {
            transform: translateX(-50%) translateY(0) scale(1);
            opacity: 1;
            z-index: 5;
            box-shadow: 0 30px 100px rgba(0,0,0,0.25);
        }

        .stacked-slide[data-position="next-1"] {
            transform: translateX(-50%) translateY(45px) scale(0.88);
            opacity: 0.35;
            z-index: 2;
        }

        .stacked-slide[data-position="next-2"] {
            transform: translateX(-50%) translateY(80px) scale(0.75);
            opacity: 0.2;
            z-index: 1;
        }

        .stacked-slide[data-position="hidden"] {
            transform: translateX(-50%) translateY(120px) scale(0.65);
            opacity: 0;
            z-index: 0;
            pointer-events: none;
        }

        @media (max-width: 1200px) {
            .stacked-slide {
                width: 85%;
            }
        }

        @media (max-width: 991px) {
            .stacked-carousel {
                height: 320px;
            }
            .stacked-slide {
                width: 90%;
            }
            .stacked-slide img {
                height: 280px;
            }
            .stacked-slide[data-position="prev-1"] {
                transform: translateX(-50%) translateY(-22px) scale(0.9);
            }
            .stacked-slide[data-position="next-1"] {
                transform: translateX(-50%) translateY(22px) scale(0.9);
            }
        }

        @media (max-width: 576px) {
            .stacked-carousel {
                height: 240px;
            }
            .stacked-slide {
                width: 95%;
            }
            .stacked-slide img {
                height: 200px;
            }
        }

        /* Section Styles */
        .section {
            padding: 100px 0;
            overflow: hidden;
        }

        .section-light {
            background: var(--bg-light);
        }

        .section-header {
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .section-header h2 .highlight {
            color: var(--primary-color);
            font-style: italic;
        }

        .section-header p {
            font-size: 16px;
            color: var(--text-secondary);
            max-width: 500px;
        }

        .section-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s ease;
        }

        .section-link:hover {
            gap: 12px;
            color: var(--primary-dark);
        }

        /* Leadership Section */
        .leader-card {
            text-align: center;
        }

        .leader-photo {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
        }

        .leader-photo::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-color);
            border-radius: 50%;
            z-index: 0;
        }

        .leader-photo::after {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            right: -6px;
            bottom: -6px;
            border: 3px solid var(--primary-color);
            border-radius: 50%;
            opacity: 0.3;
        }

        .leader-photo img {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            height: auto;
            max-height: 95%;
            object-fit: contain;
            object-position: bottom center;
            z-index: 1;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .leader-card:hover .leader-photo img {
            filter: grayscale(0%);
        }

        .leader-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .leader-position {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Leaders Grid Layout */
        .leaders-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            justify-items: center;
        }

        .leaders-grid .leader-card {
            width: 100%;
            max-width: 200px;
        }

        .leaders-grid-slot {
            display: flex;
            justify-content: center;
        }

        .leaders-grid-slot.empty-slot {
            /* Keep empty slots on desktop to maintain grid position */
        }

        .leaders-grid-slot.has-content {
            visibility: visible;
        }

        /* Tablet - hide empty slots and reflow */
        @media (max-width: 1200px) {
            .leaders-grid {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 30px;
            }

            .leaders-grid-slot {
                flex: 0 0 auto;
            }

            .leaders-grid-slot.empty-slot {
                display: none !important;
            }

            .leaders-grid .leader-card {
                max-width: 180px;
            }
        }

        @media (max-width: 992px) {
            .leaders-grid {
                gap: 25px;
            }

            .leaders-grid .leader-card {
                max-width: 160px;
            }

            .leaders-grid .leader-photo {
                width: 120px;
                height: 120px;
            }
        }

        @media (max-width: 768px) {
            .leaders-grid {
                gap: 20px;
            }

            .leaders-grid .leader-card {
                max-width: 140px;
            }

            .leaders-grid .leader-photo {
                width: 100px;
                height: 100px;
            }

            .leaders-grid .leader-name {
                font-size: 12px;
            }

            .leaders-grid .leader-position {
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {
            .leaders-grid {
                gap: 15px;
                justify-content: space-around;
            }

            .leaders-grid .leader-card {
                max-width: 130px;
            }

            .leaders-grid .leader-photo {
                width: 80px;
                height: 80px;
            }

            .leaders-grid .leader-name {
                font-size: 11px;
            }

            .leaders-grid .leader-position {
                font-size: 9px;
            }
        }

        /* News Cards */
        .news-card {
            background: var(--bg-white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }

        .news-card.featured {
            background: var(--primary-color);
            color: white;
        }

        .news-card.featured .news-meta,
        .news-card.featured .news-excerpt {
            color: rgba(255,255,255,0.8);
        }

        .news-card.featured .news-link {
            background: white;
            color: var(--primary-color);
        }

        .news-image {
            height: 200px;
            overflow: hidden;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-image img {
            transform: scale(1.1);
        }

        .news-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-meta {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .news-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-excerpt {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .news-link {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 20px;
            background: var(--bg-light);
            color: var(--primary-color);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .news-link:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Visi Misi */
        .visi-misi-card {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 40px;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .visi-misi-card h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .visi-misi-card h3 i {
            color: var(--primary-color);
        }

        /* Video Section */
        .video-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            cursor: pointer;
        }

        .video-card img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .video-card:hover .video-overlay {
            background: rgba(0,0,0,0.6);
        }

        .video-play {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 16px;
            transition: transform 0.3s ease;
        }

        .video-card:hover .video-play {
            transform: scale(1.1);
        }

        .video-title {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            padding: 0 20px;
        }

        /* Video Swiper Carousel */
        .video-swiper-container {
            position: relative;
            padding: 30px 0;
            overflow: hidden;
        }

        .videoSwiper {
            overflow: hidden;
            padding: 20px 0;
        }

        .videoSwiper .swiper-slide {
            transition: all 0.5s ease;
            opacity: 0.5;
            transform: scale(0.85);
        }

        .videoSwiper .swiper-slide-active {
            opacity: 1;
            transform: scale(1);
        }

        .video-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #000;
        }

        .video-card-thumbnail {
            width: 100%;
            aspect-ratio: 16/9;
            position: relative;
            z-index: 1;
        }

        .video-card-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .video-card:hover .video-card-thumbnail img {
            transform: scale(1.05);
        }

        .video-card-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }

        .video-card.playing .video-card-iframe {
            opacity: 1;
            visibility: visible;
        }

        .video-card.playing .video-card-thumbnail {
            opacity: 0;
        }

        .video-card-iframe iframe {
            width: 100%;
            height: 100%;
        }

        .video-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .video-card.playing .video-card-overlay {
            opacity: 0;
            pointer-events: none;
        }

        .video-card-play {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .videoSwiper .swiper-slide-active .video-card-play {
            width: 70px;
            height: 70px;
            font-size: 28px;
        }

        .video-card:hover .video-card-play {
            transform: scale(1.1);
        }

        .video-card-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .video-swiper-nav {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .video-nav-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-nav-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .video-card-play {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            .videoSwiper .swiper-slide-active .video-card-play {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }

        /* News Slider */
        .news-slider {
            padding: 20px 0;
        }

        .swiper-slide {
            height: auto;
        }

        .swiper-pagination-bullet-active {
            background: var(--primary-color);
        }

        /* Stats Section */
        .stats-section {
            background: var(--secondary-color);
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-value {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.7;
        }

        /* ============================================
           VISITOR STATISTICS SECTION
        ============================================ */
        .visitor-stats-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 80px 0;
        }

        .visitor-chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        .visitor-chart-card.main-chart {
            height: 100%;
        }

        .visitor-chart-card .chart-header {
            margin-bottom: 20px;
        }

        .visitor-chart-card .chart-header h5 {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-year-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .chart-year-badge.small {
            padding: 6px 12px;
            font-size: 11px;
        }

        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }

        .chart-container.small {
            height: 200px;
        }

        .chart-nav {
            position: absolute;
            bottom: 24px;
            left: 24px;
            display: flex;
            gap: 8px;
        }

        .chart-nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-nav-btn:hover {
            border-color: #f97316;
            color: #f97316;
            background: #fff7ed;
        }

        .chart-nav-btn i {
            font-size: 16px;
        }

        .visitor-summary {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .summary-content {
            display: flex;
            flex-direction: column;
        }

        .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }

        .summary-label {
            font-size: 12px;
            color: #64748b;
        }

        /* Visitor Stats Responsive */
        @media (max-width: 991.98px) {
            .visitor-stats-section {
                padding: 60px 0;
            }

            .chart-container {
                height: 280px;
            }

            .chart-nav {
                position: static;
                margin-top: 16px;
                justify-content: center;
            }
        }

        @media (max-width: 767.98px) {
            .visitor-stats-section {
                padding: 50px 0;
            }

            .visitor-chart-card {
                padding: 16px;
            }

            .chart-container {
                height: 250px;
            }

            .chart-container.small {
                height: 180px;
            }

            .chart-year-badge {
                font-size: 11px;
                padding: 6px 12px;
            }

            .summary-value {
                font-size: 18px;
            }
        }

        /* Contact Section */
        .contact-section {
            background: var(--bg-light);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .contact-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .contact-text h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .contact-text p {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        .contact-text a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .map-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        /* Footer */
        .footer {
            background: var(--bg-white);
            border-top: 1px solid var(--border-color);
            padding: 60px 0 30px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-brand img {
            height: 50px;
        }

        .footer-newsletter p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .newsletter-form {
            display: flex;
            gap: 8px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
        }

        .newsletter-form button {
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .newsletter-form button:hover {
            background: var(--primary-dark);
        }

        .footer-links h5 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            font-size: 14px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-bottom p {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--bg-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-color);
            color: white;
        }

        /* ============================================
           RESPONSIVE STYLES
           Desktop: > 1200px (default)
           Tablet: 768px - 1199px
           Smartphone: < 768px
        ============================================ */

        /* TABLET MODE (768px - 1199px) */
        @media (max-width: 1199px) {
            /* Navbar */
            .navbar-brand img {
                height: 40px;
            }

            .navbar-brand-text h1 {
                font-size: 13px;
            }

            .navbar-brand-text span {
                font-size: 11px;
            }

            .nav-link {
                padding: 8px 12px !important;
                font-size: 13px;
            }

            /* Hero */
            .hero-title {
                font-size: 40px;
            }

            .hero-subtitle {
                font-size: 16px;
            }

            /* Section */
            .section {
                padding: 80px 0;
            }

            .section-header h2 {
                font-size: 32px;
            }

            /* Leaders */
            .leader-photo {
                width: 180px;
                height: 180px;
            }

            .leader-name {
                font-size: 14px;
            }

            .leader-position {
                font-size: 12px;
            }

            /* News */
            .news-image {
                height: 180px;
            }

            .news-title {
                font-size: 16px;
            }

            /* Stats */
            .stat-value {
                font-size: 40px;
            }

            /* Footer */
            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form input,
            .newsletter-form button {
                width: 100%;
            }
        }

        /* TABLET SMALL (768px - 991px) */
        @media (max-width: 991px) {
            /* Navbar - Hide desktop nav, show mobile button */
            .navbar {
                padding: 12px 0;
            }

            /* Hero */
            .hero-section {
                padding-top: 70px;
            }

            .hero-content {
                padding: 30px 0;
            }

            .hero-title {
                font-size: 34px;
            }

            .hero-subtitle {
                font-size: 15px;
                margin-bottom: 30px;
            }

            .hero-buttons {
                gap: 12px;
            }

            .btn-primary-hero,
            .btn-secondary-hero {
                padding: 12px 24px;
                font-size: 14px;
            }

            /* Stacked Carousel */
            .stacked-carousel-wrapper {
                padding: 60px 15px 80px;
            }

            .stacked-carousel {
                height: 320px;
            }

            .stacked-slide {
                width: 90%;
            }

            .stacked-slide img {
                height: 280px;
            }

            /* Section */
            .section {
                padding: 60px 0;
            }

            .section-header {
                margin-bottom: 40px;
            }

            .section-header h2 {
                font-size: 28px;
            }

            .section-header p {
                font-size: 14px;
            }

            /* Leaders */
            .leader-photo {
                width: 160px;
                height: 160px;
            }

            /* News */
            .news-body {
                padding: 20px;
            }

            .news-title {
                font-size: 15px;
            }

            .news-excerpt {
                font-size: 13px;
            }

            /* Stats */
            .stats-section {
                padding: 60px 0;
            }

            .stat-value {
                font-size: 36px;
            }

            .stat-label {
                font-size: 13px;
            }

            /* Contact */
            .map-container iframe {
                height: 300px;
            }

            /* Visi Misi */
            .visi-misi-card {
                padding: 30px;
            }

            .visi-misi-card h3 {
                font-size: 20px;
            }

            /* Video */
            .video-card-play {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .videoSwiper .swiper-slide-active .video-card-play {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            /* Footer */
            .footer {
                padding: 50px 0 20px;
            }

            .footer-brand img {
                height: 40px;
            }

            .footer-links h5 {
                font-size: 13px;
                margin-bottom: 15px;
            }

            .footer-links a {
                font-size: 13px;
            }
        }

        /* SMARTPHONE MODE (< 768px) */
        @media (max-width: 767px) {
            /* Container padding */
            .container {
                padding-left: 20px;
                padding-right: 20px;
            }

            /* Navbar */
            .navbar {
                padding: 10px 0;
            }

            .navbar-brand img {
                height: 35px;
            }

            .navbar-brand-text {
                display: none !important;
            }

            /* Hero */
            .hero-section {
                min-height: auto;
                padding-top: 60px;
            }

            .hero-content {
                padding: 25px 0;
            }

            .hero-badge {
                font-size: 12px;
                padding: 6px 16px;
                margin-bottom: 16px;
            }

            .hero-title {
                font-size: 26px;
                margin-bottom: 16px;
            }

            .hero-subtitle {
                font-size: 14px;
                margin-bottom: 24px;
                padding: 0 10px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 0 20px;
            }

            .btn-primary-hero,
            .btn-secondary-hero {
                padding: 14px 20px;
                font-size: 14px;
                justify-content: center;
                width: 100%;
            }

            /* Stacked Carousel */
            .stacked-carousel-wrapper {
                padding: 40px 10px 60px;
                margin-top: 30px;
            }

            .stacked-carousel {
                height: 200px;
            }

            .stacked-slide {
                width: 95%;
            }

            .stacked-slide img {
                height: 170px;
            }

            .stacked-slide[data-position="prev-1"] {
                transform: translateX(-50%) translateY(-20px) scale(0.92);
            }

            .stacked-slide[data-position="next-1"] {
                transform: translateX(-50%) translateY(20px) scale(0.92);
            }

            /* Section */
            .section {
                padding: 50px 0;
            }

            .section-header {
                margin-bottom: 30px;
                text-align: center;
            }

            .section-header h2 {
                font-size: 24px;
            }

            .section-header p {
                font-size: 13px;
                margin: 0 auto;
            }

            .section-link {
                font-size: 13px;
            }

            /* Leaders Grid */
            .leaders-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .leader-photo {
                width: 120px;
                height: 120px;
            }

            .leader-photo::after {
                top: -4px;
                left: -4px;
                right: -4px;
                bottom: -4px;
                border-width: 2px;
            }

            .leader-name {
                font-size: 13px;
            }

            .leader-position {
                font-size: 11px;
            }

            /* News Cards */
            .news-card {
                margin-bottom: 15px;
            }

            .news-image {
                height: 160px;
            }

            .news-body {
                padding: 16px;
            }

            .news-meta {
                font-size: 11px;
                margin-bottom: 8px;
            }

            .news-title {
                font-size: 14px;
                margin-bottom: 8px;
                -webkit-line-clamp: 2;
            }

            .news-excerpt {
                font-size: 12px;
                -webkit-line-clamp: 2;
            }

            .news-link {
                padding: 8px 16px;
                font-size: 12px;
                margin-top: 12px;
            }

            /* Stats */
            .stats-section {
                padding: 40px 0;
            }

            .stat-item {
                margin-bottom: 20px;
            }

            .stat-value {
                font-size: 32px;
            }

            .stat-label {
                font-size: 12px;
            }

            /* Video */
            .video-swiper-container {
                padding: 15px 0;
            }

            .video-card-play {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }

            .videoSwiper .swiper-slide-active .video-card-play {
                width: 55px;
                height: 55px;
                font-size: 22px;
            }

            .video-card-title {
                font-size: 12px;
                padding: 15px;
            }

            .video-nav-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            /* Visi Misi */
            .visi-misi-card {
                padding: 24px;
                margin-bottom: 15px;
            }

            .visi-misi-card h3 {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .visi-misi-card p,
            .visi-misi-card li {
                font-size: 13px;
            }

            /* Contact */
            .contact-section {
                padding: 50px 0;
            }

            .contact-item {
                gap: 12px;
            }

            .contact-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .contact-text h4 {
                font-size: 13px;
            }

            .contact-text p {
                font-size: 13px;
            }

            .map-container {
                margin-top: 30px;
            }

            .map-container iframe {
                height: 250px;
            }

            /* Footer */
            .footer {
                padding: 40px 0 20px;
            }

            .footer-brand {
                justify-content: center;
                margin-bottom: 15px;
            }

            .footer-brand img {
                height: 35px;
            }

            .footer-newsletter {
                text-align: center;
            }

            .footer-newsletter p {
                font-size: 13px;
            }

            .newsletter-form {
                flex-direction: column;
                gap: 10px;
            }

            .newsletter-form input {
                padding: 12px 14px;
                font-size: 13px;
            }

            .newsletter-form button {
                padding: 12px 20px;
                font-size: 13px;
            }

            .footer-links {
                text-align: center;
                margin-top: 25px;
            }

            .footer-links h5 {
                font-size: 13px;
                margin-bottom: 12px;
            }

            .footer-links li {
                margin-bottom: 8px;
            }

            .footer-links a {
                font-size: 13px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 15px;
                margin-top: 30px;
                padding-top: 20px;
            }

            .footer-bottom p {
                font-size: 12px;
            }

            .social-links {
                justify-content: center;
            }

            .social-links a {
                width: 36px;
                height: 36px;
            }

            /* Page Header */
            .page-header {
                padding: 100px 0 50px;
            }

            .breadcrumb-nav {
                font-size: 12px;
                gap: 8px;
                margin-bottom: 15px;
                flex-wrap: wrap;
            }

            .page-title {
                font-size: 24px;
                margin-bottom: 12px;
            }

            .page-subtitle {
                font-size: 14px;
            }

            /* Mobile Menu Offcanvas */
            .offcanvas {
                max-width: 280px;
            }

            .offcanvas-header {
                padding: 20px;
            }

            .offcanvas-body {
                padding: 0 20px;
            }

            .offcanvas-body .nav-link {
                padding: 12px 0 !important;
                border-bottom: 1px solid var(--border-color);
                font-size: 15px;
            }
        }

        /* SMALL SMARTPHONE (< 480px) */
        @media (max-width: 480px) {
            .hero-title {
                font-size: 22px;
            }

            .hero-subtitle {
                font-size: 13px;
            }

            .section-header h2 {
                font-size: 20px;
            }

            .leaders-grid {
                gap: 12px;
            }

            .leader-photo {
                width: 100px;
                height: 100px;
            }

            .leader-name {
                font-size: 12px;
            }

            .leader-position {
                font-size: 10px;
            }

            .stacked-carousel {
                height: 160px;
            }

            .stacked-slide img {
                height: 140px;
            }

            .stat-value {
                font-size: 28px;
            }

            .page-title {
                font-size: 20px;
            }
        }

        /* Animation */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #0a1628 0%, #1e3a5f 100%);
            padding: 150px 0 80px;
            color: white;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .breadcrumb-nav a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-nav a:hover {
            color: white;
        }

        .breadcrumb-nav span {
            color: rgba(255,255,255,0.5);
        }

        .page-title {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .page-subtitle {
            font-size: 16px;
            color: rgba(255,255,255,0.8);
            max-width: 600px;
            line-height: 1.7;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <a href="{{ route('landing') }}" class="navbar-brand">
                <img src="{{ \App\Helpers\SettingHelper::logo() }}" alt="Logo">
                <div class="navbar-brand-text">
                    <h1>SEKRETARIAT DAERAH</h1>
                    <span>Kabupaten Katingan</span>
                </div>
            </a>

            <div class="navbar-menu">
                <div class="nav-item">
                    <a href="{{ route('landing') }}" class="nav-link {{ request()->routeIs('landing') && !request()->is('berita*') && !request()->is('layanan*') ? 'active' : '' }}">Beranda</a>
                </div>

                <div class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        Layanan <span class="arrow"></span>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('landing.layanan') }}">Layanan Bag. Pemerintahan</a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="{{ route('landing.berita') }}" class="nav-link {{ request()->routeIs('landing.berita*') ? 'active' : '' }}">Berita</a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('landing.struktur') }}" class="nav-link {{ request()->routeIs('landing.struktur') ? 'active' : '' }}">Struktur Organisasi</a>
                </div>

                <div class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        Informasi <span class="arrow"></span>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}">Info Bag. Pemerintahan</a>
                        <a href="{{ route('landing.informasi', 'informasi-kewilayahan') }}">Info Kewilayahan</a>
                        <a href="{{ route('landing.informasi', 'informasi-kerja-sama') }}">Info Kerja Sama</a>
                    </div>
                </div>

                <a href="#kontak" class="nav-btn">Hubungi Kami</a>
            </div>
        </div>
    </nav>

    <!-- Bottom Navigation Overlay -->
    <div class="bottom-overlay" id="bottomOverlay"></div>

    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-container">
            <a href="{{ route('landing') }}" class="bottom-nav-item {{ request()->routeIs('landing') && !request()->is('berita*') && !request()->is('struktur*') && !request()->is('informasi*') && !request()->is('layanan*') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('landing.berita') }}" class="bottom-nav-item {{ request()->routeIs('landing.berita*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i>
                <span>Berita</span>
            </a>

            <div class="bottom-nav-center">
                <a href="{{ route('user.login') }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Layanan</span>
                </a>
            </div>

            <a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}" class="bottom-nav-item {{ request()->routeIs('landing.informasi*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Informasi</span>
            </a>

            <div class="bottom-nav-item bottom-nav-more" id="bottomMore">
                <i class="bi bi-three-dots"></i>
                <span>Lainnya</span>
            </div>
        </div>

        <!-- Dropdown Menu (outside the button) -->
        <div class="bottom-more-dropdown" id="bottomDropdown">
            <a href="{{ route('landing.layanan') }}">
                <i class="bi bi-file-earmark-text"></i>
                Info Layanan
            </a>
            <a href="{{ route('landing.struktur') }}">
                <i class="bi bi-diagram-3"></i>
                Struktur Organisasi
            </a>
            <div class="bottom-dropdown-divider"></div>
            <a href="{{ route('landing.informasi', 'informasi-kewilayahan') }}">
                <i class="bi bi-map"></i>
                Info Kewilayahan
            </a>
            <a href="{{ route('landing.informasi', 'informasi-kerja-sama') }}">
                <i class="bi bi-people"></i>
                Info Kerja Sama
            </a>
            <div class="bottom-dropdown-divider"></div>
            <a href="#kontak">
                <i class="bi bi-telephone"></i>
                Hubungi Kami
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <img src="{{ \App\Helpers\SettingHelper::logo() }}" alt="Logo">
                    </div>
                    <div class="footer-newsletter">
                        <p>Dapatkan informasi terbaru dari kami.</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Masukkan Email Anda">
                            <button type="submit">Kirim</button>
                        </form>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="footer-links">
                        <h5>Navigasi</h5>
                        <ul>
                            <li><a href="{{ route('landing') }}">Beranda</a></li>
                            <li><a href="{{ route('landing.berita') }}">Berita</a></li>
                            <li><a href="{{ route('landing.struktur') }}">Struktur Organisasi</a></li>
                            <li><a href="#kontak">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="footer-links">
                        <h5>Layanan</h5>
                        <ul>
                            <li><a href="{{ route('user.login') }}">Portal Layanan</a></li>
                            <li><a href="{{ route('landing.informasi', 'informasi-publik-bagian-pemerintahan') }}">Informasi Publik</a></li>
                            <li><a href="#">Pengaduan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-links">
                        <h5>Kontak</h5>
                        <ul>
                            <li><i class="bi bi-geo-alt me-2"></i>{{ \App\Helpers\SettingHelper::address() }}</li>
                            <li><i class="bi bi-telephone me-2"></i>{{ \App\Helpers\SettingHelper::phone() }}</li>
                            <li><i class="bi bi-envelope me-2"></i>{{ \App\Helpers\SettingHelper::email() }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ \App\Helpers\SettingHelper::siteName() }}. All Rights Reserved.</p>
                <div class="social-links">
                    @if(\App\Helpers\SettingHelper::social('facebook'))
                        <a href="{{ \App\Helpers\SettingHelper::social('facebook') }}" target="_blank"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if(\App\Helpers\SettingHelper::social('instagram'))
                        <a href="{{ \App\Helpers\SettingHelper::social('instagram') }}" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if(\App\Helpers\SettingHelper::social('twitter'))
                        <a href="{{ \App\Helpers\SettingHelper::social('twitter') }}" target="_blank"><i class="bi bi-twitter-x"></i></a>
                    @endif
                    @if(\App\Helpers\SettingHelper::social('youtube'))
                        <a href="{{ \App\Helpers\SettingHelper::social('youtube') }}" target="_blank"><i class="bi bi-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Bottom Navigation - More Dropdown
        const bottomMore = document.getElementById('bottomMore');
        const bottomDropdown = document.getElementById('bottomDropdown');
        const bottomOverlay = document.getElementById('bottomOverlay');

        if (bottomMore) {
            bottomMore.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                bottomDropdown.classList.toggle('show');
                bottomOverlay.classList.toggle('show');
                this.classList.toggle('active');
            });
        }

        if (bottomOverlay) {
            bottomOverlay.addEventListener('click', function() {
                bottomDropdown.classList.remove('show');
                bottomOverlay.classList.remove('show');
                bottomMore.classList.remove('active');
            });
        }

        // Close dropdown when clicking links
        if (bottomDropdown) {
            bottomDropdown.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    bottomDropdown.classList.remove('show');
                    bottomOverlay.classList.remove('show');
                    bottomMore.classList.remove('active');
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
