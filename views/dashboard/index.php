<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lokanesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #ff6b6b;
            --dark-color: #1a1a1a;
            --light-color: #f8f9fa;
            --gradient-1: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --gradient-2: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-color);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: var(--gradient-1);
            color: white;
            padding: 2rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: white;
        }

        .sidebar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .user-info h6 {
            margin: 0;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            font-size: 1.1rem;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow-x: hidden;
        }

        .welcome-section {
            background: var(--gradient-1);
            margin: -2rem -2rem 2rem -2rem;
            padding: 3rem 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1592364395653-83e648b20cc2?auto=format&fit=crop&w=1920&q=80') center/cover;
            opacity: 0.1;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .quick-action {
            background: white;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .quick-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .quick-action:hover::before {
            transform: scaleX(1);
        }

        .quick-action:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .quick-action i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .quick-action:hover i {
            transform: scale(1.1);
        }

        .activity-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            background: var(--light-color);
        }

        .activity-item:hover {
            transform: translateX(5px);
            background: #f0f2f5;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .activity-item:hover .activity-icon {
            transform: scale(1.1);
        }

        .lottie-container {
            position: absolute;
            top: 50%;
            right: 2rem;
            transform: translateY(-50%);
            width: 300px;
            height: 300px;
            pointer-events: none;
            opacity: 0.9;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                padding: 1rem;
            }

            .sidebar-brand span,
            .nav-link span,
            .user-info {
                display: none;
            }

            .main-content {
                margin-left: 80px;
            }

            .nav-link {
                justify-content: center;
            }

            .user-profile {
                padding: 0.8rem;
                justify-content: center;
            }

            .lottie-container {
                width: 200px;
                height: 200px;
                opacity: 0.5;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .welcome-section {
                padding: 2rem 1rem;
            }

            .lottie-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/dashboard" class="sidebar-brand">
            <img src="https://i.pinimg.com/736x/66/dd/d0/66ddd0a43433943549bd2beb9cec5273.jpg" alt="Lokanesia">
            <span>Lokanesia</span>
        </a>

        <!-- User Profile -->
        <div class="user-profile">
            <img src="<?php echo isset($user['avatar']) ? $user['avatar'] : 'https://i.pinimg.com/736x/8f/56/c9/8f56c952730fd9885975fcae6fae1692.jpg'; ?>" 
                 alt="<?php echo $user['name'] ?? 'User'; ?>" 
                 class="user-avatar">
            <div class="user-info">
                <h6><?php echo $user['name'] ?? 'User'; ?></h6>
            </div>
        </div>

        <!-- Navigation -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/dashboard">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tourist-spots">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Destinasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dashboard/itineraries">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Rencana Perjalanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dashboard/profile">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section with Lottie -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1 class="display-5 fw-bold mb-4">Selamat Datang, <?php echo $user['name'] ?? 'User'; ?>! 👋</h1>
                <p class="lead mb-0">Mulai petualangan Anda di Indonesia</p>
            </div>
            <!-- Lottie Animation -->
            <div class="lottie-container" id="welcomeAnimation"></div>
        </div>

        <div class="container-fluid">
            <!-- Stats Row -->
            <div class="row g-4 mb-5" data-aos="fade-up">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(30, 60, 114, 0.1); color: var(--primary-color);">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="stat-value"><?php echo $totalSpots ?? 0; ?></div>
                        <div class="stat-label">Destinasi Tersimpan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(255, 107, 107, 0.1); color: var(--accent-color);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value"><?php echo $totalItineraries ?? 0; ?></div>
                        <div class="stat-label">Rencana Perjalanan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #FFC107;">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-value">Level <?php echo $user['level'] ?? 1; ?></div>
                        <div class="stat-label">Level Traveler</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(76, 175, 80, 0.1); color: #4CAF50;">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="stat-value"><?php echo $totalPopularSpots ?? 0; ?></div>
                        <div class="stat-label">Destinasi Populer</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h4 class="mb-4" data-aos="fade-up">Aksi Cepat</h4>
            <div class="row g-4 mb-5">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <a href="/tourist-spots" class="quick-action">
                        <i class="fas fa-compass"></i>
                        <h5>Jelajahi Wisata</h5>
                        <p class="text-muted">Temukan destinasi baru</p>
                    </a>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <a href="/dashboard/itineraries" class="quick-action">
                        <i class="fas fa-calendar-plus"></i>
                        <h5>Buat Rencana</h5>
                        <p class="text-muted">Rencanakan perjalanan</p>
                    </a>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <a href="/dashboard/profile" class="quick-action">
                        <i class="fas fa-user-edit"></i>
                        <h5>Edit Profil</h5>
                        <p class="text-muted">Perbarui profil Anda</p>
                    </a>
                </div>
            </div>

            <!-- Activities and Popular Spots -->
            <div class="row g-4">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="activity-card">
                        <h4 class="mb-4">Aktivitas Terbaru</h4>
                        <?php if (isset($recentActivities) && !empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon" style="background: <?php echo $activity['color']; ?>">
                                    <i class="fas <?php echo $activity['icon']; ?>"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?php echo $activity['title']; ?></h6>
                                    <p class="text-muted mb-0"><?php echo $activity['description']; ?></p>
                                    <small class="text-muted"><?php echo $activity['time']; ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-history fa-3x mb-3"></i>
                                <p>Belum ada aktivitas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="activity-card">
                        <h4 class="mb-4">Destinasi Populer</h4>
                        <?php if (isset($popularSpots) && !empty($popularSpots)): ?>
                            <?php foreach ($popularSpots as $spot): ?>
                            <div class="activity-item">
                                <img src="<?php echo $spot['image']; ?>" 
                                     alt="<?php echo $spot['name']; ?>" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1"><?php echo $spot['name']; ?></h6>
                                    <div class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $spot['rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif ($i - 0.5 <= $spot['rating']): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="text-muted ms-2"><?php echo number_format($spot['rating'], 1); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                                <p>Belum ada destinasi populer</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Welcome Animation
        const welcomeAnimation = lottie.loadAnimation({
            container: document.getElementById('welcomeAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets10.lottiefiles.com/packages/lf20_khzniaya.json'
        });

        // Logout handler
        document.getElementById('logoutBtn').addEventListener('click', async function(e) {
            e.preventDefault();
            try {
                await fetch('/logout');
                window.location.href = '/';
            } catch (error) {
                console.error('Logout error:', error);
            }
        });

        // Stats Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.textContent);
            let count = 0;
            const duration = 2000;
            const increment = target / (duration / 16);

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

        // Animate all stat values on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.stat-value').forEach(stat => {
                if (!isNaN(parseInt(stat.textContent))) {
                    animateCounter(stat);
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.style.width = '280px';
                mainContent.style.marginLeft = '280px';
            } else {
                sidebar.style.width = '0';
                mainContent.style.marginLeft = '0';
            }
        });
    </script>
</body>
</html>
