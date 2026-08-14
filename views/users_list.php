<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Portal Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/login.css">
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body class="bg-slate-950 text-gray-100 min-h-screen relative overflow-x-hidden">

    <canvas id="particles-canvas"></canvas>

    <main class="relative z-10 max-w-5xl mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">Kelola Akun User</h1>
                <p class="text-xs text-amber-400 font-medium tracking-widest uppercase mt-1">
                    Daftar User Terdaftar
                </p>
            </div>
            <a href="index.php?action=dashboard" class="admin-btn !bg-amber-400 hover:!bg-amber-500 !text-slate-950 font-bold text-xs border-none">
                ⬅️ Kembali ke Dashboard
            </a>
        </div>

        <div class="glow-card p-5 md:p-6">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $i => $u): ?>
                                <tr id="user-row-<?= $u['id']; ?>">
                                    <td><?= $i + 1; ?></td>
                                    <td class="font-semibold text-amber-400"><?= htmlspecialchars($u['username']); ?></td>
                                    <td>
                                        <span class="bg-amber-400/10 text-amber-300 text-xs px-2.5 py-1 rounded border border-amber-400/20 font-medium">
                                            <?= htmlspecialchars($u['role'] ?? 'user'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex justify-center">
                                            <?php if (strtolower($u['username']) !== 'admin'): ?>
                                                <button type="button" 
                                                        class="admin-btn btn-delete !py-1 !px-3 text-xs"
                                                        onclick="hapusUser(<?= $u['id']; ?>, '<?= htmlspecialchars($u['username']); ?>')">
                                                    Hapus
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xs text-gray-500 italic">(Admin Utama)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-8">
                                    Belum ada user terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="public/js/particles.js"></script>
    <script>
    function hapusUser(userId, username) {
        if (confirm("Apakah Anda yakin ingin menghapus user '" + username + "'?")) {
            fetch('api/delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: userId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    const row = document.getElementById('user-row-' + userId);
                    if (row) row.remove();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi!');
            });
        }
    }
    </script>
</body>
</html>
