<?php $title = 'Cari Tempat Wisata' ?>

<div class="row">
    <!-- Filter Sidebar -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Filter</h5>
                
                <form action="/search" method="GET" id="filterForm">
                    <!-- Search Query -->
                    <div class="mb-3">
                        <label class="form-label">Kata Kunci</label>
                        <input type="text" name="q" class="form-control" value="<?= $query ?>">
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="alam" <?= $category === 'alam' ? 'selected' : '' ?>>Wisata Alam</option>
                            <option value="budaya" <?= $category === 'budaya' ? 'selected' : '' ?>>Wisata Budaya</option>
                            <option value="kuliner" <?= $category === 'kuliner' ? 'selected' : '' ?>>Wisata Kuliner</option>
                            <option value="sejarah" <?= $category === 'sejarah' ? 'selected' : '' ?>>Wisata Sejarah</option>
                            <option value="religi" <?= $category === 'religi' ? 'selected' : '' ?>>Wisata Religi</option>
                            <option value="pantai" <?= $category === 'pantai' ? 'selected' : '' ?>>Wisata Pantai</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-label">Rentang Harga</label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="<?= $minPrice ?>">
                            </div>
                            <div class="col">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="<?= $maxPrice ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                        <label class="form-label">Rating Minimum</label>
                        <select name="rating" class="form-select">
                            <option value="">Semua Rating</option>
                            <?php for ($i = 4; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= isset($_GET['rating']) && $_GET['rating'] == $i ? 'selected' : '' ?>>
                                    <?= $i ?> Bintang & Up
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="rating_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_desc' ? 'selected' : '' ?>>
                                Rating Tertinggi
                            </option>
                            <option value="rating_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_asc' ? 'selected' : '' ?>>
                                Rating Terendah
                            </option>
                            <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>
                                Harga Terendah
                            </option>
                            <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>
                                Harga Tertinggi
                            </option>
                            <option value="reviews_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'reviews_desc' ? 'selected' : '' ?>>
                                Ulasan Terbanyak
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="col-md-9">
        <!-- Map -->
        <div class="card mb-4">
            <div class="card-body p-0">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>

        <!-- Results Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Hasil Pencarian</h4>
                <p class="text-muted mb-0">Ditemukan <?= $total ?> tempat wisata</p>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary active" data-view="grid">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="btn btn-outline-primary" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="row" id="resultsGrid">
            <?php foreach ($spots as $spot): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= $spot->thumbnail ?>" class="card-img-top" alt="<?= $spot->name ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $spot->name ?></h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt"></i> <?= $spot->address ?>
                            </p>
                            <div class="mb-2">
                                <span class="badge bg-primary"><?= $spot->category ?></span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-tag"></i> Rp <?= number_format($spot->ticket_price, 0, ',', '.') ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $spot->rating): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ms-1">(<?= $spot->review_count ?> ulasan)</span>
                            </div>
                            <a href="/tourist-spot/<?= (string) $spot->_id ?>" class="btn btn-primary">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Results List (Hidden by default) -->
        <div class="d-none" id="resultsList">
            <?php foreach ($spots as $spot): ?>
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?= $spot->thumbnail ?>" class="img-fluid rounded-start h-100" alt="<?= $spot->name ?>" style="object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?= $spot->name ?></h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?= $spot->address ?>
                                </p>
                                <div class="mb-2">
                                    <span class="badge bg-primary"><?= $spot->category ?></span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-tag"></i> Rp <?= number_format($spot->ticket_price, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $spot->rating): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="ms-1">(<?= $spot->review_count ?> ulasan)</span>
                                </div>
                                <p class="card-text"><?= substr($spot->description, 0, 150) ?>...</p>
                                <a href="/tourist-spot/<?= (string) $spot->_id ?>" class="btn btn-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script>
// Initialize map
const map = L.map('map').setView([-2.5489, 118.0149], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add markers for spots
const markers = [];
<?php foreach ($spots as $spot): ?>
    const marker = L.marker([<?= $spot->location->coordinates[1] ?>, <?= $spot->location->coordinates[0] ?>])
        .bindPopup(`
            <div class="text-center">
                <img src="<?= $spot->thumbnail ?>" alt="<?= $spot->name ?>" style="width: 150px; height: 100px; object-fit: cover; margin-bottom: 10px;">
                <h6><?= $spot->name ?></h6>
                <p class="mb-2"><small><?= $spot->address ?></small></p>
                <div class="mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $spot->rating): ?>
                            <i class="fas fa-star text-warning"></i>
                        <?php else: ?>
                            <i class="far fa-star text-warning"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <a href="/tourist-spot/<?= (string) $spot->_id ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
            </div>
        `)
        .addTo(map);
    markers.push(marker);
<?php endforeach; ?>

// Fit bounds to show all markers
if (markers.length > 0) {
    const group = L.featureGroup(markers);
    map.fitBounds(group.getBounds().pad(0.1));
}

// Toggle view (grid/list)
document.querySelectorAll('[data-view]').forEach(button => {
    button.addEventListener('click', () => {
        const view = button.dataset.view;
        
        // Update active button
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
        
        // Show/hide views
        if (view === 'grid') {
            document.getElementById('resultsGrid').classList.remove('d-none');
            document.getElementById('resultsList').classList.add('d-none');
        } else {
            document.getElementById('resultsGrid').classList.add('d-none');
            document.getElementById('resultsList').classList.remove('d-none');
        }
    });
});

// Get user's location if available
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
        const { latitude, longitude } = position.coords;
        
        L.marker([latitude, longitude], {
            icon: L.divIcon({
                className: 'user-location',
                html: '<i class="fas fa-circle text-primary"></i>'
            })
        })
        .bindPopup('Lokasi Anda')
        .addTo(map);
    });
}
</script>

<style>
.user-location {
    color: #0d6efd;
    font-size: 24px;
    text-align: center;
    line-height: 24px;
}
</style> 