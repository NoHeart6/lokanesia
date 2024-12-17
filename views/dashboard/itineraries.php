<?php
$title = 'Rencana Perjalanan - Dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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

        .itinerary-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.5s ease;
            height: 100%;
        }

        .itinerary-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .card-img-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .itinerary-card:hover .card-img-top {
            transform: scale(1.1);
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .card-text {
            color: #666;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-footer {
            background: transparent;
            border-top: 1px solid rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background: var(--gradient-1);
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--gradient-1);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: var(--gradient-1);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control, .form-select {
            border-radius: 15px;
            padding: 12px 20px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(30,60,114,0.1);
            border-color: var(--primary-color);
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
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* SweetAlert Custom Styles */
        .swal2-popup {
            font-family: 'Plus Jakarta Sans', sans-serif;
            border-radius: 20px;
        }

        .swal2-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-color);
        }

        .swal2-confirm {
            background: var(--gradient-1) !important;
            border-radius: 30px !important;
            padding: 12px 30px !important;
        }

        .swal2-deny, .swal2-cancel {
            border-radius: 30px !important;
            padding: 12px 30px !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/dashboard" class="sidebar-brand">
            <img src="https://i.pinimg.com/736x/66/dd/d0/66ddd0a43433943549bd2beb9cec5273.jpg" alt="Lokanesia">
            <span>Lokanesia</span>
        </a>

        <!-- User Profile -->
        <?php if (isset($user)): ?>
        <div class="user-profile">
            <img src="<?php echo isset($user['avatar']) ? $user['avatar'] : 'https://i.pinimg.com/736x/8f/56/c9/8f56c952730fd9885975fcae6fae1692.jpg'; ?>" 
                 alt="<?php echo $user['name'] ?? 'User'; ?>" 
                 class="user-avatar">
            <div class="user-info">
                <h6><?php echo $user['name'] ?? 'User'; ?></h6>
            </div>
        </div>
        <?php endif; ?>

        <!-- Navigation -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
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
                <a class="nav-link active" href="/dashboard/itineraries">
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
            <li class="nav-item mt-4">
                <a class="nav-link" href="/dashboard/settings">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h2 class="mb-3">Rencana Perjalanan</h2>
                <p class="lead mb-0">Kelola dan atur rencana perjalanan wisata Anda dengan mudah</p>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Action Button -->
            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItineraryModal">
                    <i class="fas fa-plus"></i> Tambah Rencana
                </button>
            </div>

            <!-- Itinerary List -->
            <div class="row" id="itinerariesList">
                <?php if (!empty($itineraries)): ?>
                    <?php foreach ($itineraries as $itinerary): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="itinerary-card">
                                <div class="card-img-container">
                                    <img src="<?php echo htmlspecialchars($itinerary['spot']['image_url']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($itinerary['spot']['name']); ?>">
                                    <span class="badge <?php echo $itinerary['statusClass']; ?> status-badge">
                                        <?php echo $itinerary['statusLabel']; ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($itinerary['spot']['name']); ?></h5>
                                    <p class="card-text">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo (new DateTime($itinerary['date']))->format('d M Y'); ?>
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php echo htmlspecialchars($itinerary['spot']['address']); ?>
                                    </p>
                                    <?php if (!empty($itinerary['notes'])): ?>
                                        <p class="card-text">
                                            <i class="fas fa-sticky-note"></i> 
                                            <?php echo htmlspecialchars($itinerary['notes']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="/tourist-spot/<?php echo $itinerary['spot']['_id']; ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary btn-sm" onclick="editItinerary('<?php echo $itinerary['_id']; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteItinerary('<?php echo $itinerary['_id']; ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h4>Belum Ada Rencana Perjalanan</h4>
                            <p class="text-muted mb-4">Ayo mulai rencanakan perjalanan wisata Anda!</p>
                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItineraryModal">
                                    <i class="fas fa-plus"></i> Tambah Rencana
                                </button>
                                <a href="/tourist-spots" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> Cari Tempat Wisata
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Itinerary Modal -->
    <div class="modal fade" id="editItineraryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rencana Perjalanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editItineraryForm">
                        <input type="hidden" name="itinerary_id" id="editItineraryId">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" name="date" id="editDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="notes" id="editNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="updateItinerary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Itinerary Modal -->
    <div class="modal fade" id="addItineraryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rencana Perjalanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addItineraryForm">
                        <div class="mb-3">
                            <label class="form-label">Tempat Wisata</label>
                            <select class="form-select" name="spot_id" id="addSpotId" required>
                                <option value="">Pilih Tempat Wisata</option>
                                <?php foreach ($spots as $spot): ?>
                                    <option value="<?php echo $spot['_id']; ?>">
                                        <?php echo htmlspecialchars($spot['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" name="date" id="addDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="notes" id="addNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="createItinerary">Tambah Rencana</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Edit Itinerary
        async function editItinerary(id) {
            try {
                const response = await fetch(`/api/itineraries/${id}`);
                const data = await response.json();
                
                if (response.ok) {
                    document.getElementById('editItineraryId').value = id;
                    document.getElementById('editDate').value = data.data.date;
                    document.getElementById('editNotes').value = data.data.notes;
                    
                    new bootstrap.Modal(document.getElementById('editItineraryModal')).show();
                } else {
                    throw new Error(data.message || 'Gagal mengambil data rencana perjalanan');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonText: 'Tutup'
                });
            }
        }

        // Update Itinerary
        document.getElementById('updateItinerary')?.addEventListener('click', async function() {
            const id = document.getElementById('editItineraryId').value;
            const date = document.getElementById('editDate').value;
            const notes = document.getElementById('editNotes').value;

            if (!date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal kunjungan harus diisi',
                    confirmButtonText: 'OK'
                });
                return;
            }

            try {
                const response = await fetch(`/api/itineraries/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ date, notes })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Rencana perjalanan berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Gagal memperbarui rencana perjalanan');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonText: 'Tutup'
                });
            }
        });

        // Delete Itinerary
        async function deleteItinerary(id) {
            const result = await Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Rencana perjalanan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/api/itineraries/${id}/delete`, {
                        method: 'POST'
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Rencana perjalanan berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menghapus rencana perjalanan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        }

        // Create Itinerary
        document.getElementById('createItinerary')?.addEventListener('click', async function() {
            const spotId = document.getElementById('addSpotId').value;
            const date = document.getElementById('addDate').value;
            const notes = document.getElementById('addNotes').value;

            if (!spotId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih tempat wisata',
                    confirmButtonText: 'OK'
                });
                return;
            }
            if (!date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal kunjungan harus diisi',
                    confirmButtonText: 'OK'
                });
                return;
            }

            try {
                const response = await fetch('/api/itineraries/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ spot_id: spotId, date, notes })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Rencana perjalanan berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Gagal menambahkan rencana perjalanan');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonText: 'Tutup'
                });
            }
        });

        // Logout
        document.getElementById('logoutBtn')?.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const result = await Swal.fire({
                title: 'Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('/logout');
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Logout',
                            text: 'Anda akan dialihkan ke halaman login',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '/login';
                        });
                    } else {
                        throw new Error('Gagal logout');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat logout: ' + error.message,
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        });
    </script>
</body>
</html> 