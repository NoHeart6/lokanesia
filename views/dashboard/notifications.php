<?php $title = 'Notifikasi' ?>

<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Notifikasi</h4>
        <?php if (!empty($notifications)): ?>
            <button type="button" class="btn btn-outline-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i> Tandai Semua Sudah Dibaca
            </button>
        <?php endif; ?>
    </div>

    <!-- Notifications List -->
    <?php if (empty($notifications)): ?>
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-bell fa-3x text-muted"></i>
            </div>
            <h5>Tidak ada notifikasi</h5>
            <p class="text-muted">Anda akan menerima notifikasi ketika ada aktivitas baru</p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item <?= !$notification->read ? 'bg-light' : '' ?>">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <?php if ($notification->type === 'review'): ?>
                                    <div class="bg-warning text-white rounded p-2">
                                        <i class="fas fa-star"></i>
                                    </div>
                                <?php elseif ($notification->type === 'itinerary'): ?>
                                    <div class="bg-primary text-white rounded p-2">
                                        <i class="fas fa-route"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-info text-white rounded p-2">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1"><?= $notification->message ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i>
                                            <?= $notification->created_at->toDateTime()->format('d M Y H:i') ?>
                                        </small>
                                    </div>
                                    <?php if (!$notification->read): ?>
                                        <button type="button" class="btn btn-link text-primary p-0" onclick="markAsRead('<?= (string) $notification->_id ?>')">
                                            <i class="fas fa-check"></i> Tandai Sudah Dibaca
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if (isset($notification->action_url)): ?>
                                    <div class="mt-2">
                                        <a href="<?= $notification->action_url ?>" class="btn btn-sm btn-primary">
                                            <?= $notification->action_text ?? 'Lihat Detail' ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
// Mark single notification as read
async function markAsRead(id) {
    try {
        const response = await axios.put(`/api/notifications/${id}/read`);
        if (response.data.status === 'success') {
            window.location.reload();
        }
    } catch (error) {
        const message = error.response?.data?.message || 'Terjadi kesalahan';
        alert(message);
    }
}

// Mark all notifications as read
async function markAllAsRead() {
    try {
        const response = await axios.put('/api/notifications/read-all');
        if (response.data.status === 'success') {
            window.location.reload();
        }
    } catch (error) {
        const message = error.response?.data?.message || 'Terjadi kesalahan';
        alert(message);
    }
}
</script> 