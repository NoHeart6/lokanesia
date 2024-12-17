<?php $title = 'Reset Password' ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="/assets/images/logo.png" alt="Lokanesia" height="50" class="mb-4">
                    <h4>Reset Password</h4>
                    <p class="text-muted">Masukkan password baru Anda</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                        <script>
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 3000);
                        </script>
                    </div>
                <?php endif; ?>

                <form action="/reset-password" method="POST" id="resetPasswordForm">
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" required minlength="6" id="password">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            Password minimal 6 karakter
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="6" id="passwordConfirmation">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-key"></i> Reset Password
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
function togglePassword(button) {
    const input = button.previousElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Handle form submission
document.getElementById('resetPasswordForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('passwordConfirmation');
    
    if (password.value !== passwordConfirmation.value) {
        alert('Konfirmasi password tidak sesuai');
        return;
    }
    
    try {
        const response = await axios.post('/api/auth/reset-password', new FormData(e.target));
        if (response.data.status === 'success') {
            alert('Password berhasil direset');
            window.location.href = '/login';
        }
    } catch (error) {
        const message = error.response?.data?.message || 'Terjadi kesalahan';
        alert(message);
    }
});
</script> 