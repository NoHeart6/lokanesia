<?php 
$title = isset($spot['name']) ? $spot['name'] : 'Tempat Wisata';
$title .= ' - Lokanesia';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .map-container {
            height: 400px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .gallery-img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .gallery-img:hover {
            transform: scale(1.05);
        }
        .review-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .category-badge {
            background: #e9ecef;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #495057;
        }
        .price-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .rating-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            gap: 5px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 25px;
            color: #ddd;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Lokanesia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/tourist-spots">Tempat Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/itineraries">Rencana Perjalanan</a>
                    </li>
                </ul>
                <?php if (isset($user)): ?>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
                            <i class="bx bx-user"></i> <?php echo htmlspecialchars($user['name']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/dashboard/profile"><i class="bx bx-user-circle"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="/dashboard/settings"><i class="bx bx-cog"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="bx bx-log-out"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="/tourist-spots" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Main Image -->
                <img src="<?php echo htmlspecialchars($spot['image_url'] ?? 'https://placehold.co/600x400?text=Wisata+Indonesia'); ?>" 
                     class="img-fluid rounded mb-4" 
                     style="width: 100%; height: 400px; object-fit: cover;"
                     alt="<?php echo htmlspecialchars($spot['name']); ?>"
                     onerror="this.src='https://placehold.co/600x400?text=Wisata+Indonesia'">

                <!-- Basic Info -->
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 mb-2"><?php echo htmlspecialchars($spot['name']); ?></h1>
                            <span class="category-badge"><?php echo htmlspecialchars($spot['category'] ?? 'Umum'); ?></span>
                        </div>
                        <span class="price-badge">
                            <?php if (($spot['ticket_price'] ?? 0) > 0): ?>
                                Rp <?php echo number_format($spot['ticket_price'], 0, ',', '.'); ?>
                            <?php else: ?>
                                Gratis
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="rating-stars mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bx <?php echo $i <= round($spot['rating'] ?? 0) ? 'bxs-star' : 'bx-star'; ?>"></i>
                            <?php endfor; ?>
                            <span class="text-muted ms-2">
                                <?php echo number_format($spot['rating'] ?? 0, 1); ?> 
                                (<?php echo $spot['review_count'] ?? 0; ?> ulasan)
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            <i class="bx bx-map-pin"></i> 
                            <?php echo htmlspecialchars($spot['address'] ?? 'Alamat tidak tersedia'); ?>
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5>Deskripsi</h5>
                        <p><?php echo nl2br(htmlspecialchars($spot['description'] ?? 'Deskripsi tidak tersedia')); ?></p>
                    </div>

                    <!-- Location Map -->
                    <div class="mb-4">
                        <h5>Lokasi</h5>
                        <div id="map" class="map-container"></div>
                    </div>

                    <!-- Action Buttons -->
                    <?php if (isset($user)): ?>
                        <div class="d-flex gap-2 mb-4">
                            <!-- Save Spot Button -->
                            <button class="btn btn-outline-primary" id="saveSpotBtn" <?php echo $savedStatus ? 'disabled' : ''; ?>>
                                <i class="bx bx-bookmark"></i> 
                                <?php echo $savedStatus ? 'Sudah Tersimpan' : 'Simpan Tempat'; ?>
                            </button>
                            
                            <!-- Add Review Button -->
                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                <i class="bx bx-star"></i> Tambah Ulasan
                            </button>
                            
                            <!-- Add to Itinerary Button -->
                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addItineraryModal">
                                <i class="bx bx-calendar-plus"></i> Tambah ke Rencana
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle"></i> 
                            Silakan <a href="/login">login</a> untuk menyimpan tempat, memberikan ulasan, atau menambahkan ke rencana perjalanan.
                        </div>
                    <?php endif; ?>

                    <!-- Reviews Section -->
                    <div class="mb-4">
                        <h5>Ulasan Pengunjung</h5>
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="card review-card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <?php echo htmlspecialchars($review['user_name']); ?>
                                            <?php if (isset($user) && $review['user_id'] === $user['_id']): ?>
                                                <span class="badge bg-primary">Ulasan Anda</span>
                                            <?php endif; ?>
                                        </h6>
                                        <div class="rating-stars mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bx <?php echo $i <= $review['rating'] ? 'bxs-star' : 'bx-star'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="card-text"><?php echo htmlspecialchars($review['comment']); ?></p>
                                        <small class="text-muted">
                                            <?php 
                                            $date = new DateTime($review['created_at']);
                                            echo $date->format('d M Y H:i'); 
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-message-square-detail" style="font-size: 3rem;"></i>
                                <p class="mt-2">Belum ada ulasan untuk tempat ini</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Operating Hours -->
                <div class="info-card mb-4">
                    <h5>Jam Operasional</h5>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($spot['operating_hours'] ?? 'Informasi jam operasional belum tersedia'); ?>
                    </p>
                </div>

                <!-- Nearby Places -->
                <div class="info-card">
                    <h5>Tempat Wisata Terdekat</h5>
                    <?php if (!empty($nearbySpots)): ?>
                        <?php foreach ($nearbySpots as $nearbySpot): ?>
                            <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="<?php echo htmlspecialchars($nearbySpot['image_url'] ?? 'https://placehold.co/600x400?text=Wisata+Indonesia'); ?>" 
                                             class="img-fluid rounded-start" 
                                             style="height: 100%; object-fit: cover;"
                                             alt="<?php echo htmlspecialchars($nearbySpot['name']); ?>">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo htmlspecialchars($nearbySpot['name']); ?></h6>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <i class="bx bx-map-pin"></i> 
                                                    <?php echo htmlspecialchars($nearbySpot['address']); ?>
                                                </small>
                                            </p>
                                            <a href="/tourist-spot/<?php echo $nearbySpot['_id']; ?>" class="stretched-link"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada tempat wisata terdekat</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Review Modal -->
    <div class="modal fade" id="addReviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>">
                                    <label for="star<?php echo $i; ?>"><i class="bx bx-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Komentar</label>
                            <textarea class="form-control" name="comment" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitReview">Kirim Ulasan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Itinerary Modal -->
    <div class="modal fade" id="addItineraryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah ke Rencana Perjalanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="itineraryForm">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="notes" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitItinerary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const spotId = '<?php echo $spot['_id']; ?>';
            const spotLat = <?php echo isset($spot['location']['coordinates'][1]) ? $spot['location']['coordinates'][1] : 'null'; ?>;
            const spotLng = <?php echo isset($spot['location']['coordinates'][0]) ? $spot['location']['coordinates'][0] : 'null'; ?>;

            // Initialize map if coordinates exist
            if (spotLat && spotLng) {
                const map = L.map('map').setView([spotLat, spotLng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([spotLat, spotLng])
                    .addTo(map)
                    .bindPopup('<?php echo htmlspecialchars($spot['name']); ?>');
            } else {
                document.getElementById('map').innerHTML = '<div class="alert alert-warning">Lokasi tidak tersedia</div>';
            }

            // Save Spot
            document.getElementById('saveSpotBtn')?.addEventListener('click', async function() {
                try {
                    console.log('Attempting to save spot with ID:', spotId);
                    
                    // Disable button to prevent double submission
                    this.disabled = true;
                    this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Menyimpan...';
                    
                    const response = await fetch(`/api/tourist-spots/${spotId}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({}) // Mengirim body kosong untuk memastikan request valid
                    });
                    
                    console.log('Response status:', response.status);
                    const data = await response.json();
                    console.log('Response data:', data);
                    
                    if (response.ok) {
                        // Tampilkan pesan sukses
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success alert-dismissible fade show';
                        successAlert.innerHTML = `
                            <i class="bx bx-check"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        this.parentElement.insertBefore(successAlert, this);
                        
                        // Update tombol
                        this.innerHTML = '<i class="bx bx-bookmark"></i> Sudah Tersimpan';
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-primary');
                        this.disabled = true;
                        
                        // Refresh halaman setelah 2 detik
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        // Reset tombol jika gagal
                        this.disabled = false;
                        this.innerHTML = '<i class="bx bx-bookmark"></i> Simpan Tempat';
                        
                        if (response.status === 401) {
                            alert('Silakan login terlebih dahulu untuk menyimpan tempat wisata');
                            window.location.href = '/login';
                        } else {
                            throw new Error(data.message || 'Gagal menyimpan tempat');
                        }
                    }
                } catch (error) {
                    console.error('Error saving spot:', error);
                    
                    // Reset tombol jika terjadi error
                    this.disabled = false;
                    this.innerHTML = '<i class="bx bx-bookmark"></i> Simpan Tempat';
                    
                    // Tampilkan pesan error
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                    errorAlert.innerHTML = `
                        <i class="bx bx-error"></i> ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    this.parentElement.insertBefore(errorAlert, this);
                }
            });

            // Submit Review
            document.getElementById('submitReview')?.addEventListener('click', async function() {
                const form = document.getElementById('reviewForm');
                const rating = form.querySelector('input[name="rating"]:checked')?.value;
                const comment = form.querySelector('textarea[name="comment"]').value;

                if (!rating || !comment) {
                    alert('Mohon isi rating dan komentar');
                    return;
                }

                try {
                    const response = await fetch(`/api/tourist-spots/${spotId}/review`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ rating, comment })
                    });
                    const data = await response.json();
                    
                    if (response.ok) {
                        alert('Ulasan berhasil ditambahkan!');
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan ulasan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message);
                }
            });

            // Submit Itinerary
            document.getElementById('submitItinerary')?.addEventListener('click', async function() {
                const form = document.getElementById('itineraryForm');
                const date = form.querySelector('input[name="date"]').value;
                const notes = form.querySelector('textarea[name="notes"]').value;

                if (!date || !notes) {
                    alert('Mohon isi tanggal dan catatan');
                    return;
                }

                try {
                    const response = await fetch(`/api/tourist-spots/${spotId}/itinerary`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ date, notes })
                    });
                    const data = await response.json();
                    
                    if (response.ok) {
                        alert('Berhasil ditambahkan ke rencana perjalanan!');
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan ke rencana perjalanan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message);
                }
            });
        });
    </script>
</body>
</html> 