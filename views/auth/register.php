<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LokaNesia - Jelajahi Keindahan Indonesia</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #FF3CAC;
            --gradient-1: #784BA0;
            --gradient-2: #2B86C5;
            --dark: #13151A;
            --light: #FFFFFF;
            --success: #00FFA3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: var(--dark);
            color: var(--light);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .page-container {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            position: relative;
        }

        .hero-side {
            position: relative;
            padding: 4rem;
            background: linear-gradient(135deg, rgba(19, 21, 26, 0.95), rgba(19, 21, 26, 0.8)), 
                        url('https://source.unsplash.com/featured/1200x800?indonesia,culture') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255, 60, 172, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(0, 255, 163, 0.15) 0%, transparent 40%);
            filter: blur(60px);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }

        @keyframes bgPulse {
            0% { opacity: 0.5; transform: scale(1); }
            100% { opacity: 0.8; transform: scale(1.1); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .brand-title {
            font-family: 'Unbounded', cursive;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--light), var(--success));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleFloat 3s ease-in-out infinite;
        }

        @keyframes titleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .hero-text {
            font-size: 1.25rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stats-container {
            display: flex;
            gap: 4rem;
            margin-top: 4rem;
        }

        .stat-item {
            animation: fadeInRight 1s ease-out;
        }

        .stat-value {
            font-family: 'Unbounded', cursive;
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--success));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out;
            animation-fill-mode: both;
        }

        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.4s; }
        .feature-card:nth-child(4) { animation-delay: 0.6s; }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--gradient-2));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--light);
            position: relative;
            overflow: hidden;
        }

        .feature-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(transparent, rgba(255, 255, 255, 0.2));
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon::after {
            transform: translateY(0);
        }

        .feature-title {
            font-family: 'Unbounded', cursive;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--light);
        }

        .feature-desc {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .register-side {
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
            overflow: hidden;
        }

        .register-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(255, 60, 172, 0.1), transparent 70%);
            animation: glowPulse 4s ease-in-out infinite alternate;
        }

        @keyframes glowPulse {
            0% { opacity: 0.3; }
            100% { opacity: 0.7; }
        }

        .register-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .register-title {
            font-family: 'Unbounded', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--primary), var(--gradient-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .register-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 60px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 0 1.5rem 0 3rem;
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 60, 172, 0.1);
            outline: none;
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 1.2rem;
        }

        .form-control:focus + i {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-register {
            width: 100%;
            height: 60px;
            background: linear-gradient(45deg, var(--primary), var(--gradient-2));
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 2rem;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 60, 172, 0.2);
        }

        .divider {
            margin: 2rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .login-section {
            text-align: center;
        }

        .login-text {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
        }

        .btn-login {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: transparent;
            border: 2px solid var(--primary);
            border-radius: 16px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 60, 172, 0.1), transparent);
            transition: 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 1200px) {
            .page-container {
                grid-template-columns: 1fr;
            }

            .hero-side {
                min-height: auto;
                padding: 3rem;
            }

            .stats-container {
                gap: 2rem;
            }

            .brand-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .hero-side,
            .register-side {
                padding: 2rem;
            }

            .brand-title {
                font-size: 2.5rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-container {
                flex-direction: column;
                gap: 1.5rem;
                align-items: center;
                text-align: center;
            }

            .stat-value {
                font-size: 2.5rem;
            }
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <section class="hero-side">
            <div class="animated-bg"></div>
            <div class="hero-content">
                <h1 class="brand-title">LokaNesia</h1>
                <p class="hero-text">
                    Bergabunglah dengan komunitas petualang Indonesia. Temukan destinasi menakjubkan, 
                    bagikan pengalaman unik, dan jadilah bagian dari perjalanan luar biasa di Nusantara.
                </p>

                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-value">500+</div>
                        <div class="stat-label">Destinasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">50K+</div>
                        <div class="stat-label">Traveler</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">34</div>
                        <div class="stat-label">Provinsi</div>
                    </div>
                </div>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="feature-title">Peta Interaktif</h3>
                    <p class="feature-desc">Temukan dan jelajahi destinasi wisata dengan mudah melalui peta interaktif kami</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3 class="feature-title">Galeri Visual</h3>
                    <p class="feature-desc">Nikmati koleksi foto dan video berkualitas tinggi dari berbagai destinasi</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <h3 class="feature-title">Panduan Lengkap</h3>
                    <p class="feature-desc">Dapatkan informasi detail dan tips perjalanan dari para ahli wisata</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Komunitas</h3>
                    <p class="feature-desc">Bergabung dengan komunitas traveler Indonesia dan bagikan pengalaman Anda</p>
                </div>
            </div>
        </section>

        <section class="register-side">
            <div class="register-header">
                <h2 class="register-title">Bergabung Sekarang</h2>
                <p class="register-subtitle">Mulai petualangan Anda di Indonesia</p>
            </div>

            <form id="registerForm" action="/register" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" required>
                    <i class="fas fa-user"></i>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Alamat Email" required>
                    <i class="fas fa-envelope"></i>
                </div>
                
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                    <i class="fas fa-lock"></i>
                </div>

                <button type="submit" class="btn-register">
                    <span class="loading-spinner"></span>
                    Daftar Sekarang
                </button>

                <div class="divider">
                    <span>atau</span>
                </div>

                <div class="login-section">
                    <p class="login-text">Sudah memiliki akun?</p>
                    <a href="/login" class="btn-login">Masuk</a>
                </div>
            </form>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.btn-register');
            const spinner = this.querySelector('.loading-spinner');
            const btnText = submitBtn.textContent;
            
            try {
                submitBtn.disabled = true;
                spinner.style.display = 'inline-block';
                submitBtn.textContent = 'Memproses...';
                
                const response = await fetch('/register', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selamat Bergabung di LokaNesia!',
                        text: 'Akun Anda berhasil dibuat',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#13151A',
                        color: '#FFFFFF',
                        iconColor: '#FF3CAC'
                    }).then(() => {
                        window.location.href = data.data.redirect;
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mendaftar',
                    text: error.message || 'Mohon periksa kembali data yang Anda masukkan',
                    confirmButtonColor: '#FF3CAC',
                    background: '#13151A',
                    color: '#FFFFFF'
                });
            } finally {
                submitBtn.disabled = false;
                spinner.style.display = 'none';
                submitBtn.textContent = btnText;
            }
        });

        // Form field animations
        const formControls = document.querySelectorAll('.form-control');
        formControls.forEach(input => {
            const icon = input.nextElementSibling;
            
            input.addEventListener('focus', () => {
                icon.style.color = 'var(--primary)';
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    icon.style.color = 'rgba(255, 255, 255, 0.4)';
                }
            });
        });
    </script>
</body>
</html>
