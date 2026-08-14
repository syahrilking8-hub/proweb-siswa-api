<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body class="login-body">

    <canvas id="particles-canvas"></canvas>

    <div class="login-card">
        <h1 class="login-title">Portal Data Siswa</h1>

        <?php if (!empty($error)): ?>
            <div class="login-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login">
            <div class="login-field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus autocomplete="username">
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
            </div>

            <div class="login-field">
                <label for="role">Akses Sebagai</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin (Full Control)</option>
                    <option value="user">User (Read Only)</option>
                </select>
            </div>

            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="mt-6 text-center border-t border-slate-800/80 pt-4">
            <p class="text-xs text-gray-400">
                Belum punya akun? 
                <a href="index.php?action=register" class="text-amber-400 hover:underline font-semibold">
                    Sign Up / Daftar
                </a>
            </p>
        </div>
    </div>

    <script src="public/js/particles.js"></script>
</body>
</html>
