<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/login.css">
    <link rel="stylesheet" href="public/css/admin.css">
    <style>
        /* FIX Lapisan Canvas */
        #particles-canvas {
            position: fixed !important;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            pointer-events: none !important;
            z-index: 0 !important;
        }

        /* FIX Modal Overlay Transparan */
        .custom-modal-overlay {
            position: fixed !important;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.82) !important;
            backdrop-filter: blur(6px);
            z-index: 99999 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-y: auto;
        }

        /* Input type date styling untuk dark theme */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-slate-950 text-gray-100 min-h-screen relative overflow-x-hidden">

    <canvas id="particles-canvas"></canvas>

    <main class="relative z-20 max-w-5xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">Portal Data Siswa</h1>
                <p class="text-xs text-amber-400 font-medium tracking-widest uppercase mt-1">
                    Role: <span class="bg-amber-400/10 px-2 py-0.5 rounded text-amber-300"><?= htmlspecialchars($currentUser['role'] ?? 'User'); ?></span>
                </p>
            </div>
            <a href="index.php?action=logout" class="admin-btn btn-delete text-xs">
                Logout
            </a>
        </div>

        <!-- Toolbar -->
        <div class="mb-6 flex items-center justify-between gap-4">
            <button type="button" onclick="openTambahModal()" class="admin-btn cursor-pointer relative z-30">
                <svg fill="none" class="w-4 h-4" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </button>

            <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                <a href="index.php?action=users" class="admin-btn !bg-amber-400 hover:!bg-amber-500 !text-slate-950 font-bold border-none">
                    <svg fill="none" class="w-4 h-4 stroke-slate-950" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola User
                </a>
            <?php endif; ?>
        </div>

        <!-- Tabel Data Siswa -->
        <div class="glow-card p-5 md:p-6 relative z-30">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1; ?></td>
                                    <td class="font-semibold text-amber-400"><?= htmlspecialchars($s['nis'] ?? '-'); ?></td>
                                    <td class="text-white"><?= htmlspecialchars($s['nama'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($s['alamat'] ?? ''); ?></td>
                                    <td>
                                        <div class="flex justify-center gap-2">
                                            <!-- Tombol Detail / Lihat Biodata -->
                                            <button type="button" 
                                                    onclick="openDetailModal('<?= htmlspecialchars($s['nis'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['nama'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['tempat_lahir'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['tanggal_lahir'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['alamat'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['hobi'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['cita_cita'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['foto'] ?? '', ENT_QUOTES); ?>')"
                                                    class="admin-btn btn-delete !bg-slate-900/80 hover:!bg-sky-500/20 !text-sky-400 !border-sky-500/30 cursor-pointer !py-1 !px-3 text-xs">
                                                 Detail
                                            </button>

                                            <!-- Tombol Edit -->
                                            <button type="button" 
                                                    onclick="openEditModal('<?= $s['id']; ?>', '<?= htmlspecialchars($s['nis'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['nama'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['alamat'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['tempat_lahir'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['tanggal_lahir'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['hobi'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($s['cita_cita'] ?? '', ENT_QUOTES); ?>')"
                                                    class="admin-btn cursor-pointer !py-1 !px-3 text-xs">
                                                Edit
                                            </button>
                                            
                                            <!-- Tombol Hapus -->
                                            <button type="button" 
                                                    onclick="openDeleteModal('<?= $s['id']; ?>', '<?= htmlspecialchars($s['nama'] ?? '', ENT_QUOTES); ?>')"
                                                    class="admin-btn btn-delete cursor-pointer !py-1 !px-3 text-xs">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-gray-400 py-8">
                                    Belum ada data siswa.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- 1. MODAL FORM (TAMBAH / EDIT DATA) -->
    <div id="studentModal" class="custom-modal-overlay" style="display: none;">
        <div class="login-card w-full max-w-lg relative z-50 my-8 max-h-[90vh] overflow-y-auto">
            <h2 id="modalTitle" class="login-title">Tambah Data Siswa</h2>
            
            <form id="studentForm" method="POST" action="index.php?action=store" enctype="multipart/form-data">
                <input type="hidden" name="id" id="student_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="login-field">
                        <label for="nis">NIS</label>
                        <input type="text" name="nis" id="nis" required placeholder="Masukkan NIS">
                    </div>

                    <div class="login-field">
                        <label for="nama">Nama Siswa</label>
                        <input type="text" name="nama" id="nama" required placeholder="Masukkan Nama">
                    </div>
                </div>

                <!-- Input Tempat & Tanggal Lahir (TTL) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="login-field">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" placeholder="Contoh: Bandung">
                    </div>

                    <div class="login-field">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir">
                    </div>
                </div>

                <div class="login-field">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" required placeholder="Masukkan Alamat">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="login-field">
                        <label for="hobi">Hobi</label>
                        <input type="text" name="hobi" id="hobi" placeholder="Contoh: Coding / Editing">
                    </div>

                    <div class="login-field">
                        <label for="cita_cita">Cita - Cita</label>
                        <input type="text" name="cita_cita" id="cita_cita" placeholder="Contoh: Software Engineer">
                    </div>
                </div>

                <!-- Input Upload Foto (Choose File Transparan) -->
                <div class="login-field">
                    <label for="foto">Foto Profil Siswa</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="!py-2 text-xs file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-400 file:text-slate-950 hover:file:bg-amber-300 file:cursor-pointer">
                    <span id="fotoHelp" class="text-[10px] text-gray-400 mt-1 block">*Kosongkan jika tidak ingin mengubah foto saat Edit.</span>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal('studentModal')" class="admin-btn btn-delete flex-1 text-center justify-center !py-2.5 cursor-pointer">Batal</button>
                    <button type="submit" class="login-btn flex-1 !mt-0 cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL LIHAT DETAIL BIODATA DIRI (KARTU BIODATA) -->
    <div id="detailModal" class="custom-modal-overlay" style="display: none;">
        <div class="login-card w-full max-w-md relative z-50 text-center border border-amber-500/30">
            <h2 class="login-title !mb-4">Biodata Diri Siswa</h2>
            
            <!-- Foto Profil Circular -->
            <div class="relative w-32 h-32 mx-auto mb-6 rounded-full overflow-hidden border-4 border-amber-400 shadow-lg shadow-amber-500/20">
                <img id="detail_foto" src="public/uploads/default.png" alt="Foto Siswa" class="w-full h-full object-cover">
            </div>

            <!-- Tabel Detail Biodata -->
            <div class="bg-slate-900/80 rounded-xl p-4 text-left border border-slate-800 space-y-3 text-sm">
                <div class="flex border-b border-slate-800 pb-2">
                    <span class="w-28 text-gray-400 font-medium">NIS</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_nis" class="font-bold text-amber-400"></span>
                </div>
                <div class="flex border-b border-slate-800 pb-2">
                    <span class="w-28 text-gray-400 font-medium">NAMA</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_nama" class="font-semibold text-white"></span>
                </div>
                <div class="flex border-b border-slate-800 pb-2">
                    <span class="w-28 text-gray-400 font-medium">TTL</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_ttl" class="text-gray-200"></span>
                </div>
                <div class="flex border-b border-slate-800 pb-2">
                    <span class="w-28 text-gray-400 font-medium">ALAMAT</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_alamat" class="text-gray-200"></span>
                </div>
                <div class="flex border-b border-slate-800 pb-2">
                    <span class="w-28 text-gray-400 font-medium">HOBI</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_hobi" class="text-gray-200"></span>
                </div>
                <div class="flex">
                    <span class="w-28 text-gray-400 font-medium">CITA - CITA</span>
                    <span class="text-gray-400 mr-2">:</span>
                    <span id="detail_cita" class="text-gray-200"></span>
                </div>
            </div>

            <div class="mt-6">
                <button type="button" onclick="closeModal('detailModal')" class="login-btn w-full !mt-0 cursor-pointer">Tutup Biodata</button>
            </div>
        </div>
    </div>

    <!-- 3. MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" class="custom-modal-overlay" style="display: none;">
        <div class="login-card w-full max-w-sm text-center relative z-50">
            <h2 class="login-title !mb-2">Hapus Data?</h2>
            <p id="deleteText" class="text-gray-300 text-sm mb-6">Apakah Anda yakin ingin menghapus data siswa ini?</p>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="admin-btn flex-1 text-center justify-center !py-2.5 cursor-pointer">Batal</button>
                <a id="confirmDeleteLink" href="#" class="admin-btn btn-delete flex-1 text-center justify-center !py-2.5 cursor-pointer">Hapus</a>
            </div>
        </div>
    </div>

    <!-- SCRIPT HANDLER -->
    <script>
        function openTambahModal() {
            document.getElementById('studentForm').action = 'index.php?action=store';
            document.getElementById('modalTitle').textContent = 'Tambah Data Siswa';
            document.getElementById('student_id').value = '';
            document.getElementById('nis').value = '';
            document.getElementById('nama').value = '';
            document.getElementById('tempat_lahir').value = '';
            document.getElementById('tanggal_lahir').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('hobi').value = '';
            document.getElementById('cita_cita').value = '';
            document.getElementById('fotoHelp').style.display = 'none';
            document.getElementById('studentModal').style.display = 'flex';
        }

        function openEditModal(id, nis, nama, alamat, tempat_lahir, tanggal_lahir, hobi, cita_cita) {
            document.getElementById('studentForm').action = 'index.php?action=update';
            document.getElementById('modalTitle').textContent = 'Edit Data Siswa';
            document.getElementById('student_id').value = id;
            document.getElementById('nis').value = nis;
            document.getElementById('nama').value = nama;
            document.getElementById('alamat').value = alamat;
            document.getElementById('tempat_lahir').value = tempat_lahir || '';
            document.getElementById('tanggal_lahir').value = tanggal_lahir || '';
            document.getElementById('hobi').value = hobi || '';
            document.getElementById('cita_cita').value = cita_cita || '';
            document.getElementById('fotoHelp').style.display = 'block';
            document.getElementById('studentModal').style.display = 'flex';
        }

        function openDetailModal(nis, nama, tempat_lahir, tanggal_lahir, alamat, hobi, cita_cita, foto) {
            document.getElementById('detail_nis').textContent = nis || '-';
            document.getElementById('detail_nama').textContent = nama || '-';
            
            // Format TTL (Tempat, Tanggal Lahir)
            let ttl = '-';
            if (tempat_lahir && tanggal_lahir) {
                ttl = tempat_lahir + ', ' + tanggal_lahir;
            } else if (tempat_lahir) {
                ttl = tempat_lahir;
            } else if (tanggal_lahir) {
                ttl = tanggal_lahir;
            }
            document.getElementById('detail_ttl').textContent = ttl;

            document.getElementById('detail_alamat').textContent = alamat || '-';
            document.getElementById('detail_hobi').textContent = hobi || '-';
            document.getElementById('detail_cita').textContent = cita_cita || '-';

            // Foto Handler
            const fotoImg = document.getElementById('detail_foto');
            if (foto && foto.trim() !== '') {
                fotoImg.src = 'public/uploads/' + foto;
            } else {
                fotoImg.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nama) + '&background=f59e0b&color=0f172a&size=128';
            }

            document.getElementById('detailModal').style.display = 'flex';
        }

        function openDeleteModal(id, nama) {
            document.getElementById('deleteText').textContent = 'Apakah Anda yakin ingin menghapus data "' + nama + '"?';
            document.getElementById('confirmDeleteLink').href = 'index.php?action=delete&id=' + id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>

    <script src="public/js/particles.js"></script>
</body>
</html>
