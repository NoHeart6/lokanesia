<?php $title = $spot->name ?>

<style>
    .hero-section {
        position: relative;
        height: 70vh;
        overflow: hidden;
    }
    
    .hero-section img {
        height: 70vh;
        object-fit: cover;
        filter: brightness(0.9);
    }
    
    .hero-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
        padding: 6rem 0 2rem;
    }
    
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .quick-info-item {
        padding: 1.5rem;
        text-align: center;
        border-radius: 15px;
        background: rgba(30, 60, 114, 0.05);
        transition: all 0.3s ease;
    }
    
    .quick-info-icon {
        font-size: 2rem;
        color: #1e3c72;
        margin-bottom: 1rem;
    }
    
    .btn-custom {
        background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .btn-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div id="spotGallery" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($spot->images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= $image ?>" class="d-block w-100" alt="<?= $spot->name ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#spotGallery" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#spotGallery" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    
    <div class="position-absolute bottom-0 start-0 w-100 hero-overlay">
        <div class="container">
            <h1 class="text-white display-4 fw-bold mb-3"><?= $spot->name ?></h1>
            <p class="text-white fs-5 mb-0">
                <i class="fas fa-map-marker-alt me-2"></i> <?= $spot->address ?>
            </p>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Quick Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="quick-info-item">
                                <div class="quick-info-icon">
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                                <div class="rating-stars mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $spot->rating): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-0"><?= number_format($spot->rating, 1) ?> (<?= $spot->review_count ?> ulasan)</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="quick-info-item">
                                <div class="quick-info-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <p class="mb-0">Rp <?= number_format($spot->ticket_price, 0, ',', '.') ?></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="quick-info-item">
                                <div class="quick-info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <p class="mb-0"><?= $spot->operating_hours ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Tentang Tempat Ini</h4>
                    <p class="lead mb-0"><?= nl2br($spot->description) ?></p>
                </div>
            </div>

            <!-- Facilities -->
            <?php if (!empty($spot->facilities)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Fasilitas</h4>
                        <div class="row g-3">
                            <?php foreach ($spot->facilities as $facility): ?>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <?= $facility ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Location -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Lokasi</h4>
                    <div id="map" style="height: 400px; border-radius: 15px;" class="mb-4"></div>
                    <button class="btn btn-custom" onclick="getDirections()">
                        <i class="fas fa-directions me-2"></i> Petunjuk Arah
                    </button>
                </div>
            </div>

            <!-- Reviews -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Ulasan</h4>
                        <?php if (isset($user)): ?>
                            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="fas fa-star me-2"></i> Tulis Ulasan
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <p class="text-muted">Belum ada ulasan untuk tempat ini.</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="p-4 bg-light rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1"><?= $review->user_name ?></h5>
                                        <div class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $review->rating): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= $review->created_at->toDateTime()->format('d M Y') ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br($review->comment) ?></p>
                            </div>
                        <?php endforeach; ?>

                        <?php if (count($reviews) === 10): ?>
                            <div class="text-center mt-4">
                                <a href="/tourist-spot/<?= (string) $spot->_id ?>/reviews" class="btn btn-custom">
                                    Lihat Semua Ulasan
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Add to Itinerary -->
            <?php if (isset($user)): ?>
                <div class="card sticky-top" style="top: 2rem;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Tambahkan ke Rencana Perjalanan</h5>
                        <form action="/api/itineraries/add-spot" method="POST" id="addToItineraryForm">
                            <input type="hidden" name="tourist_spot_id" value="<?= (string) $spot->_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Rencana Perjalanan</label>
                                <select name="itinerary_id" class="form-select" required>
                                    <option value="">Pilih Rencana Perjalanan</option>
                                    <?php foreach ($userItineraries as $itinerary): ?>
                                        <option value="<?= (string) $itinerary->_id ?>">
                                            <?= $itinerary->title ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Kunjungan</label>
                                <input type="date" name="visit_date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan (opsional)</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-custom w-100">
                                <i class="fas fa-plus me-2"></i> Tambahkan
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Review Modal -->
<?php if (isset($user)): ?>
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Tulis Ulasan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/api/tourist-spots/<?= (string) $spot->_id ?>/reviews" method="POST" id="reviewForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-stars text-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                                    <label for="star<?= $i ?>">
                                        <i class="far fa-star"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Komentar</label>
                            <textarea name="comment" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
// Initialize map
const map = L.map('map').setView([
    <?= $spot->location->coordinates[1] ?>,
    <?= $spot->location->coordinates[0] ?>
], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add marker for the spot
const marker = L.marker([
    <?= $spot->location->coordinates[1] ?>,
    <?= $spot->location->coordinates[0] ?>
])
.bindPopup(`
    <div class="text-center">
        <h6><?= $spot->name ?></h6>
        <p class="mb-0"><small><?= $spot->address ?></small></p>
    </div>
`)
.addTo(map);

// Get directions
function getDirections() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const { latitude, longitude } = position.coords;
            const url = `https://www.openstreetmap.org/directions?from=${latitude},${longitude}&to=<?= $spot->location->coordinates[1] ?>,<?= $spot->location->coordinates[0] ?>`;
            window.open(url, '_blank');
        });
    } else {
        alert('Geolocation is not supported by your browser');
    }
}

// Handle review form submission
const reviewForm = document.getElementById('reviewForm');
if (reviewForm) {
    reviewForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await axios.post(reviewForm.action, new FormData(reviewForm));
            if (response.data.status === 'success') {
                window.location.reload();
            }
        } catch (error) {
            alert(error.response?.data?.message || 'Terjadi kesalahan');
        }
    });
}

// Handle add to itinerary form submission
const addToItineraryForm = document.getElementById('addToItineraryForm');
if (addToItineraryForm) {
    addToItineraryForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await axios.post(addToItineraryForm.action, new FormData(addToItineraryForm));
            if (response.data.status === 'success') {
                alert('Berhasil ditambahkan ke rencana perjalanan');
                addToItineraryForm.reset();
            }
        } catch (error) {
            alert(error.response?.data?.message || 'Terjadi kesalahan');
        }
    });
}
</script>

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
    }

    h1, h2, h3, h4, h5 {
        font-family: 'Playfair Display', serif;
    }

    /* Hero Section Enhancement */
    #spotGallery .carousel-item img {
        height: 70vh !important;
        object-fit: cover;
        filter: brightness(0.9);
    }

    .hero-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, transparent 100%) !important;
        padding: 6rem 0 2rem !important;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    /* Card Enhancements */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .card-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .card-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    /* Quick Info Enhancement */
    .quick-info-item {
        padding: 1.5rem;
        text-align: center;
        border-radius: 15px;
        background: rgba(30, 60, 114, 0.05);
        transition: all 0.3s ease;
    }

    .quick-info-item:hover {
        background: rgba(30, 60, 114, 0.1);
        transform: translateY(-3px);
    }

    .quick-info-icon {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    /* Review Section Enhancement */
    .review-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-rating {
        color: #ffd700;
    }

    /* Button Enhancements */
    .btn-custom {
        background: var(--gradient-2);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    /* Nearby Spots Enhancement */
    .nearby-spot {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 15px;
        background: white;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .nearby-spot:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .nearby-spot img {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
    }

    .nearby-spot-info {
        margin-left: 1rem;
    }

    /* Share Buttons Enhancement */
    .share-buttons {
        display: flex;
        gap: 1rem;
    }

    .share-button {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-color);
        color: var(--primary-color);
        transition: all 0.3s ease;
    }

    .share-button:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
    }

    /* Map Enhancement */
    #map {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* Modal Enhancement */
    .modal-content {
        border-radius: 20px;
        border: none;
    }

    .modal-header {
        background: var(--gradient-1);
        color: white;
        border-radius: 20px 20px 0 0;
    }

    .modal-body {
        padding: 2rem;
    }

    /* Rating Stars Enhancement */
    .rating-stars {
        color: #ffd700;
        font-size: 1.2rem;
    }

    /* Facilities Enhancement */
    .facility-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(30, 60, 114, 0.05);
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .facility-item:hover {
        background: rgba(30, 60, 114, 0.1);
        transform: translateX(5px);
    }

    .facility-icon {
        color: var(--accent-color);
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .quick-info-item {
            margin-bottom: 1rem;
        }

        .nearby-spot {
            flex-direction: column;
            text-align: center;
        }

        .nearby-spot img {
            margin-bottom: 1rem;
        }

        .nearby-spot-info {
            margin-left: 0;
        }
    }
</style> 