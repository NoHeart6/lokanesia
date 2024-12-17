<?php $title = 'Lupa Password' ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="/assets/images/logo.png" alt="Lokanesia" height="50" class="mb-4">
                    <h4>Lupa Password?</h4>
                    <p class="text-muted">Masukkan email Anda untuk mereset password</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <form action="/forgot-password" method="POST" id="forgotPasswordForm">
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-paper-plane"></i> Kirim Link Reset Password
                    </button>

                    <div class="text-center">
                        <p class="mb-0">
                            <a href="/login" class="text-decoration-none">
                                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission
document.getElementById('forgotPasswordForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    try {
        const response = await axios.post('/api/auth/forgot-password', new FormData(e.target));
        if (response.data.status === 'success') {
            alert('Link reset password telah dikirim ke email Anda');
            e.target.reset();
        }
    } catch (error) {
        const message = error.response?.data?.message || 'Terjadi kesalahan';
        alert(message);
    }
});
</script> 