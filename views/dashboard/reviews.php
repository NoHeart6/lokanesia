<?php include __DIR__ . '/../components/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <?php include __DIR__ . '/../components/dashboard/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Ulasan Saya</h1>
            
            <?php if (empty($reviews)): ?>
                <div class="text-center py-8">
                    <p class="text-gray-600">Anda belum memberikan ulasan apapun.</p>
                    <a href="/tourist-spots" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Jelajahi Tempat Wisata
                    </a>
                </div>
            <?php else: ?>
                <div class="grid gap-6">
                    <?php foreach ($reviews as $review): ?>
                        <div class="border rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold">
                                        <?= htmlspecialchars($review['spot']['name']) ?>
                                    </h3>
                                    <div class="flex items-center my-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-yellow-400"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-yellow-400"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <?= htmlspecialchars($review['created_at']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?> 