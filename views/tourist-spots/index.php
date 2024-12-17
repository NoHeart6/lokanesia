<?php
$title = 'Tempat Wisata - Lokanesia';

// Connect to MongoDB
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongoClient->lokanesia_db->tourist_spots;
    
    // Get popular spots
    $popularSpots = $collection->find(
        [],
        [
            'sort' => ['rating' => -1],
            'limit' => 6
        ]
    )->toArray();
} catch (Exception $e) {
    $error = $e->getMessage();
    $popularSpots = [];
}

// Category icons mapping
$categoryIcons = [
    'Alam' => 'fa-mountain',
    'Budaya' => 'fa-landmark',
    'Sejarah' => 'fa-monument',
    'Hiburan' => 'fa-store',
    'Kuliner' => 'fa-utensils',
    'Religi' => 'fa-mosque'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        #map {
            height: 500px;
            width: 100%;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .search-container {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .tourist-spot-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: all 0.5s ease;
            position: relative;
        }

        .tourist-spot-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .spot-image-container {
            position: relative;
            overflow: hidden;
            height: 280px;
        }

        .spot-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .tourist-spot-card:hover .spot-image {
            transform: scale(1.1);
        }

        .spot-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 100px 20px 20px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
        }

        .category-indicator {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.95);
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1;
        }

        .spot-price {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-1);
            color: white;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1;
        }

        .spot-content {
            padding: 25px;
        }

        .spot-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .spot-location {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .spot-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
        }

        .rating-stars {
            color: #ffc107;
            display: flex;
            gap: 2px;
        }

        .review-count {
            color: #666;
            font-size: 0.9rem;
        }

        .spot-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .search-container {
            background: white;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .search-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .map-container {
            position: relative;
            margin-bottom: 40px;
        }

        .map-overlay {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .view-options {
            display: flex;
            gap: 10px;
        }

        .view-option {
            padding: 8px 15px;
            border-radius: 20px;
            background: white;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-option.active {
            background: var(--gradient-1);
            color: white;
        }

        @media (max-width: 768px) {
            .spot-image-container {
                height: 220px;
            }

            .spot-content {
                padding: 20px;
            }

            .spot-title {
                font-size: 1.2rem;
            }
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
                <a class="nav-link active" href="/tourist-spots">
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
            <li class="nav-item mt-4">
                <a class="nav-link" href="/dashboard/settings">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <?php if (isset($user)): ?>
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="section-header">
                <h2 class="section-title">Jelajahi Destinasi Wisata</h2>
                <div class="view-options">
                    <div class="view-option active">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="view-option">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>

            <!-- Map with Overlay -->
            <div class="map-container">
                <div id="map"></div>
                <div class="map-overlay">
                    <div class="text-muted mb-2">Total Destinasi</div>
                    <h4 class="mb-0"><?php echo count($popularSpots ?? []); ?> Lokasi</h4>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="search-container">
                <h5 class="search-title">Filter Pencarian</h5>
                <form id="searchForm" class="row g-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchQuery" name="q" placeholder="Cari tempat wisata...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="category" name="category">
                            <option value="">Semua Kategori</option>
                            <option value="Alam">Wisata Alam</option>
                            <option value="Budaya">Wisata Budaya</option>
                            <option value="Sejarah">Wisata Sejarah</option>
                            <option value="Hiburan">Wisata Hiburan</option>
                            <option value="Kuliner">Wisata Kuliner</option>
                            <option value="Religi">Wisata Religi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="priceRange" name="price">
                            <option value="">Semua Harga</option>
                            <option value="0-50000">< Rp50.000</option>
                            <option value="50000-100000">Rp50.000 - Rp100.000</option>
                            <option value="100000-200000">Rp100.000 - Rp200.000</option>
                            <option value="200000">≥ Rp200.000</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Popular Spots -->
            <h4 class="section-title">Rekomendasi Wisata Populer</h4>
            <div class="row" id="popularSpots">
                <?php if (!empty($popularSpots)): ?>
                    <?php foreach ($popularSpots as $spot): ?>
                        <div class="col-md-4">
                            <div class="tourist-spot-card">
                                <div class="spot-image-container">
                                    <img src="<?php echo htmlspecialchars($spot->image_url); ?>" 
                                         class="spot-image" 
                                         alt="<?php echo htmlspecialchars($spot->name); ?>">
                                    <div class="category-indicator">
                                        <i class="fas <?php echo $categoryIcons[$spot->category] ?? 'fa-map-marker-alt'; ?>"></i>
                                        <?php echo htmlspecialchars($spot->category); ?>
                                    </div>
                                    <?php if (isset($spot->price) && $spot->price > 0): ?>
                                    <div class="spot-price">
                                        <i class="fas fa-tag"></i> Rp<?php echo number_format($spot->price, 0, ',', '.'); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="spot-content">
                                    <h5 class="spot-title">
                                        <?php echo htmlspecialchars($spot->name); ?>
                                    </h5>
                                    <div class="spot-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($spot->address); ?>
                                    </div>
                                    <div class="spot-rating">
                                        <div class="rating-stars">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $spot->rating): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php elseif($i - 0.5 <= $spot->rating): ?>
                                                    <i class="fas fa-star-half-alt"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="review-count">(<?php echo $spot->review_count; ?> ulasan)</span>
                                    </div>
                                    <div class="spot-footer">
                                        <div class="spot-distance">
                                            <i class="fas fa-route text-primary"></i>
                                            <span class="ms-2"><?php echo isset($spot->distance) ? $spot->distance . ' km' : '0 km'; ?></span>
                                        </div>
                                        <a href="/tourist-spot/<?php echo $spot->_id; ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-right"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fs-4 me-2"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Tidak ada data tempat wisata</h5>
                                    <p class="mb-0">Mohon maaf, saat ini tidak ada tempat wisata yang tersedia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Define category icons and default images
        const categoryIcon = {
            'Alam': 'fa-mountain',
            'Budaya': 'fa-landmark',
            'Sejarah': 'fa-monument',
            'Hiburan': 'fa-store',
            'Kuliner': 'fa-utensils',
            'Religi': 'fa-mosque'
        };

        const defaultImages = {
            'Budaya': 'https://th.bing.com/th/id/OIP.MLYlbuwZXJSESMuou4O8dwHaLV?rs=1&pid=ImgDetMain', // Candi Borobudur
            'Religi': 'https://th.bing.com/th/id/OIP.Y4K92KeoC5iwzmNarYzp1wHaFP?rs=1&pid=ImgDetMain', // Masjid Istiqlal
            'Alam': {
                'Pantai': 'https://upload.wikimedia.org/wikipedia/commons/f/fe/Kuta_Beach_(6924448550).jpg',
                'Kawah': 'https://2.bp.blogspot.com/-2den5uSbDA8/WGCKBykqA0I/AAAAAAAAAss/XVVDfkahAiwCRWZzYjl8_K1zHsCVCg2_gCLcB/s1600/Kawah%2Bputih%2B1.jpg'
            },
            'Hiburan': 'https://1.bp.blogspot.com/-PKXxc0V04hI/VeU5pp0MZBI/AAAAAAAAArE/4zsr3Xq2tpk/s1600/Malioboro%2Bstreet%2Bscene%2Bat%2Bnight.jpg'
        };

        // Function to get appropriate default image
        function getDefaultImage(category, name = '') {
            if (category === 'Alam') {
                if (name.toLowerCase().includes('pantai') || name.toLowerCase().includes('beach')) {
                    return defaultImages.Alam.Pantai;
                } else if (name.toLowerCase().includes('kawah')) {
                    return defaultImages.Alam.Kawah;
                }
                return defaultImages.Alam.Pantai; // default to Pantai Kuta
            }
            return defaultImages[category] || defaultImages.Hiburan;
        }

        // Search functionality
        async function performSearch(form) {
            const container = document.getElementById('popularSpots');
            const formData = new FormData(form);
            const searchParams = new URLSearchParams();
            
            const query = formData.get('q') || '';
            const category = formData.get('category') || '';
            const priceRange = document.getElementById('priceRange').value;
            
            searchParams.append('q', query);
            searchParams.append('category', category);
            if (priceRange) {
                const [min, max] = priceRange.split('-');
                searchParams.append('minPrice', min);
                searchParams.append('maxPrice', max || '1000000000');
            }
            
            try {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Mencari tempat wisata...</p>
                    </div>
                `;

                const response = await fetch('/api/tourist-spots/search?' + searchParams.toString());
                if (!response.ok) throw new Error('Terjadi kesalahan saat mencari tempat wisata');

                const data = await response.json();
                
                // Clear existing markers
                map.eachLayer((layer) => {
                    if (layer instanceof L.Marker) map.removeLayer(layer);
                });

                if (!data.data || data.data.length === 0) {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fs-4 me-2"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Tidak Ditemukan</h5>
                                        <p class="mb-0">Tidak ditemukan tempat wisata yang sesuai dengan kriteria pencarian.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    map.setView([-2.5489, 118.0149], 5);
                    return;
                }

                // Update spots list
                container.innerHTML = data.data.map(spot => `
                    <div class="col-md-4">
                        <div class="tourist-spot-card">
                            <div class="spot-image-container">
                                <img src="${spot.image_url}" 
                                     class="spot-image" 
                                     alt="${spot.name}">
                                <div class="category-indicator">
                                    <i class="fas ${categoryIcon[spot.category] || 'fa-map-marker-alt'}"></i>
                                    ${spot.category || 'Umum'}
                                </div>
                                ${spot.price > 0 ? `
                                <div class="spot-price">
                                    <i class="fas fa-tag"></i> Rp${Number(spot.price).toLocaleString('id-ID')}
                                </div>
                                ` : ''}
                            </div>
                            <div class="spot-content">
                                <h5 class="spot-title">${spot.name}</h5>
                                <div class="spot-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    ${spot.address}
                                </div>
                                <div class="spot-rating">
                                    <div class="rating-stars">
                                        ${generateStars(spot.rating)}
                                    </div>
                                    <span class="review-count">(${spot.review_count} ulasan)</span>
                                </div>
                                <div class="spot-footer">
                                    <div class="spot-distance">
                                        <i class="fas fa-route text-primary"></i>
                                        <span class="ms-2">${spot.distance ? spot.distance + ' km' : '0 km'}</span>
                                    </div>
                                    <a href="/tourist-spot/${spot._id}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-right"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');

                // Update map markers
                const bounds = L.latLngBounds();
                let hasValidMarkers = false;
                
                data.data.forEach(spot => {
                    if (spot.location?.coordinates) {
                        const marker = L.marker([spot.location.coordinates[1], spot.location.coordinates[0]])
                            .bindPopup(`
                                <div class="text-center">
                                    <strong>${spot.name}</strong><br>
                                    <small>${spot.address}</small><br>
                                    <a href="/tourist-spot/${spot._id}" class="btn btn-sm btn-primary mt-2">
                                        Lihat Detail
                                    </a>
                                </div>
                            `)
                            .addTo(map);
                            
                        bounds.extend([spot.location.coordinates[1], spot.location.coordinates[0]]);
                        hasValidMarkers = true;
                    }
                });

                if (hasValidMarkers) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                } else {
                    map.setView([-2.5489, 118.0149], 5);
                }

            } catch (error) {
                console.error('Search Error:', error);
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fs-4 me-2"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Error</h5>
                                    <p class="mb-0">${error.message}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        // Helper function to generate star ratings
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star"></i>';
                } else if (i - 0.5 <= rating) {
                    stars += '<i class="fas fa-star-half-alt"></i>';
                } else {
                    stars += '<i class="far fa-star"></i>';
                }
            }
            return stars;
        }

        // Event Listeners
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch(this);
        });

        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('searchForm').dispatchEvent(new Event('submit'));
        });

        document.getElementById('logoutBtn')?.addEventListener('click', async function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                try {
                    const response = await fetch('/logout');
                    if (response.ok) {
                        window.location.href = '/login';
                    } else {
                        throw new Error('Gagal logout');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat logout: ' + error.message);
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth > 768) {
                sidebar.style.width = '280px';
                mainContent.style.marginLeft = '280px';
            } else {
                sidebar.style.width = '0';
                mainContent.style.marginLeft = '0';
            }
        });

        // Update the initial spots display
        document.addEventListener('DOMContentLoaded', function() {
            const spotCards = document.querySelectorAll('.tourist-spot-card');
            spotCards.forEach(card => {
                const img = card.querySelector('.spot-image');
                const category = card.querySelector('.category-indicator').textContent.trim();
                if (!img.src || img.src.includes('default-spot.jpg')) {
                    img.src = getDefaultImage(category);
                }
                img.onerror = function() {
                    this.src = getDefaultImage(category);
                };
            });
        });
    </script>
</body>
</html> 