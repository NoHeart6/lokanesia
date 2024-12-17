<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Lokanesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #ff6b6b;
            --gradient-1: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --gradient-2: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .profile-header {
            background: var(--gradient-1);
            padding: 6rem 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1592364395653-83e648b20cc2?auto=format&fit=crop&w=1920&q=80') center/cover;
            opacity: 0.1;
        }

        .profile-avatar-container {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            object-fit: cover;
            background: white;
        }

        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--accent-color);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .upload-btn:hover {
            transform: scale(1.1);
        }

        .upload-btn input {
            display: none;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-top: -100px;
            position: relative;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            border: 2px solid #eee;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .btn-primary {
            background: var(--gradient-1);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 10px;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: var(--gradient-2);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 10px;
            color: white;
        }

        .btn-secondary:hover {
            opacity: 0.9;
            color: white;
        }

        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        #imagePreview {
            max-width: 200px;
            margin: 1rem auto;
            display: none;
            border-radius: 10px;
        }

        .preview-container {
            text-align: center;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                padding: 4rem 0;
            }

            .profile-avatar-container {
                width: 150px;
                height: 150px;
            }

            .profile-card {
                margin-top: -50px;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Alert Messages -->
    <div class="alert alert-success" id="successAlert" role="alert"></div>
    <div class="alert alert-danger" id="errorAlert" role="alert"></div>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container text-center">
            <div class="profile-avatar-container">
                <img src="<?php echo !empty($user['avatar']) ? $user['avatar'] : 'https://i.pinimg.com/736x/8f/56/c9/8f56c952730fd9885975fcae6fae1692.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($user['name']); ?>" 
                     class="profile-avatar"
                     id="avatarImage">
                <label class="upload-btn">
                    <input type="file" id="avatar" accept="image/*">
                    <i class="fas fa-camera text-white"></i>
                </label>
            </div>
            <h2 class="mb-2"><?php echo htmlspecialchars($user['name']); ?></h2>
            <p class="mb-0 text-white-50">@<?php echo htmlspecialchars($user['username']); ?></p>
            <p class="mb-0 text-white-50">Member sejak <?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value"><?php echo $totalReviews; ?></div>
                    <div class="stat-label">Ulasan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo $totalItineraries; ?></div>
                    <div class="stat-label">Rencana Perjalanan</div>
                </div>
            </div>

            <!-- Recent Activity -->
            <?php if (!empty($activity)): ?>
            <div class="recent-activity mb-4">
                <h4 class="mb-3">Aktivitas Terbaru</h4>
                <?php foreach ($activity as $item): ?>
                    <div class="activity-item p-3 bg-light rounded mb-2">
                        <?php if ($item['type'] === 'review'): ?>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $item['data']['spot']['image'] ?? 'https://placehold.co/50x50?text=No+Image'; ?>" 
                                     alt="<?php echo htmlspecialchars($item['data']['spot']['name']); ?>"
                                     class="rounded me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <p class="mb-1">
                                        <strong>Mengulas <?php echo htmlspecialchars($item['data']['spot']['name']); ?></strong>
                                    </p>
                                    <div class="text-warning mb-1">
                                        <?php for ($i = 0; $i < $item['data']['rating']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (!empty($item['data']['comment'])): ?>
                                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($item['data']['comment']); ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted"><?php echo date('d M Y H:i', strtotime($item['created_at'])); ?></small>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $item['data']['spot']['image'] ?? 'https://placehold.co/50x50?text=No+Image'; ?>" 
                                     alt="<?php echo htmlspecialchars($item['data']['spot']['name']); ?>"
                                     class="rounded me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <p class="mb-1">
                                        <strong>Merencanakan kunjungan ke <?php echo htmlspecialchars($item['data']['spot']['name']); ?></strong>
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php echo date('d F Y', strtotime($item['data']['date'])); ?>
                                    </p>
                                    <?php if (!empty($item['data']['notes'])): ?>
                                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($item['data']['notes']); ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted"><?php echo date('d M Y H:i', strtotime($item['created_at'])); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Preview Container -->
            <div class="preview-container">
                <img id="imagePreview" class="shadow">
            </div>

            <!-- Profile Form -->
            <form id="profileForm" class="text-center">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="/dashboard" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show alert function
        function showAlert(type, message) {
            const alert = document.getElementById(type + 'Alert');
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

        // Image preview
        document.getElementById('avatar').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Show preview
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);

            // Upload immediately when file is selected
            const formData = new FormData();
            formData.append('avatar', file);

            try {
                const response = await fetch('/dashboard/upload-avatar', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    document.getElementById('avatarImage').src = result.data.avatar_url;
                    showAlert('success', 'Foto profil berhasil diperbarui');
                    setTimeout(() => {
                        preview.style.display = 'none';
                    }, 1000);
                } else {
                    showAlert('error', result.message || 'Gagal mengupload foto');
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat mengupload foto');
            }
        });

        // Profile update
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                name: formData.get('name')
            };

            try {
                const response = await fetch('/dashboard/update-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('success', 'Nama berhasil diperbarui');
                    document.querySelector('h2').textContent = data.name;
                } else {
                    showAlert('error', result.message || 'Gagal memperbarui nama');
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat memperbarui nama');
            }
        });

        // Stats animation
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.textContent = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Animate stats on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.stat-value').forEach(stat => {
                const value = parseInt(stat.textContent);
                if (!isNaN(value)) {
                    animateValue(stat, 0, value, 1000);
                }
            });
        });
    </script>
</body>
</html>