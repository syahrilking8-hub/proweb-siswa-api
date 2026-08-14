<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Portal Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/login.css">
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body class="login-body">

    <canvas id="particles-canvas"></canvas>

    <div class="login-card">
        <h1 class="login-title">Daftar Akun Baru</h1>

        <?php if (!empty($error)): ?>
            <div class="login-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-3 rounded-lg text-sm mb-4 text-center">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register">
            <div class="login-field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username baru" required autofocus autocomplete="off">
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="new-password">
            </div>

            <div class="login-field">
                <label for="role">Daftar Sebagai</label>
                <select id="role" name="role" required>
                    <option value="user">User (Read Only)</option>
                    <option value="admin">Admin (Full Control)</option>
                </select>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="index.php?action=login" class="admin-btn btn-delete flex-1 text-center justify-center !py-2.5">
                    Batal
                </a>
                <button type="submit" class="login-btn flex-1 !mt-0">
                    Daftar
                </button>
            </div>
        </form>

        <div class="mt-6 text-center border-t border-slate-800/80 pt-4">
            <p class="text-xs text-gray-400">
                Sudah punya akun? 
                <a href="index.php?action=login" class="text-amber-400 hover:underline font-semibold">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    <script src="public/js/particles.js"></script>
</body>
</html>
