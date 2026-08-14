document.addEventListener('DOMContentLoaded', () => {
    // 1. Element Modal
    const studentModal = document.getElementById('studentModal');
    const deleteModal = document.getElementById('deleteModal');
    
    // 2. Element Tombol
    const btnAdd = document.getElementById('btnAddStudent');
    const btnCancelForm = document.getElementById('btnCancelForm');
    const btnCancelDelete = document.getElementById('btnCancelDelete');
    
    // 3. Element Form
    const studentForm = document.getElementById('studentForm');
    const modalTitle = document.getElementById('modalTitle');
    const inputId = document.getElementById('student_id');
    const inputNis = document.getElementById('nis');
    const inputNama = document.getElementById('nama');
    const inputAlamat = document.getElementById('alamat');
    
    // 4. Element Modal Hapus
    const deleteText = document.getElementById('deleteText');
    const confirmDeleteLink = document.getElementById('confirmDeleteLink');

    // --- FITUR TAMBAH SISWA ---
    if (btnAdd) {
        btnAdd.addEventListener('click', () => {
            studentForm.action = 'index.php?action=store';
            modalTitle.textContent = 'Tambah Data Siswa';
            if (inputId) inputId.value = '';
            if (inputNis) inputNis.value = '';
            if (inputNama) inputNama.value = '';
            if (inputAlamat) inputAlamat.value = '';
            studentModal.style.display = 'flex'; // Langsung ubah style display
        });
    }

    // --- FITUR EDIT SISWA ---
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            studentForm.action = 'index.php?action=update';
            modalTitle.textContent = 'Edit Data Siswa';
            
            if (inputId) inputId.value = btn.getAttribute('data-id') || '';
            if (inputNis) inputNis.value = btn.getAttribute('data-nis') || '';
            if (inputNama) inputNama.value = btn.getAttribute('data-nama') || '';
            if (inputAlamat) inputAlamat.value = btn.getAttribute('data-alamat') || '';
            
            studentModal.style.display = 'flex'; // Langsung ubah style display
        });
    });

    // --- FITUR HAPUS SISWA ---
    document.querySelectorAll('button.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (btn.id === 'btnCancelForm') return;
            e.stopPropagation();

            const id = btn.getAttribute('data-id');
            const nama = btn.getAttribute('data-nama') || 'siswa ini';

            if (id) {
                if (deleteText) deleteText.textContent = `Apakah Anda yakin ingin menghapus data "${nama}"?`;
                if (confirmDeleteLink) confirmDeleteLink.href = `index.php?action=delete&id=${id}`;
                deleteModal.style.display = 'flex'; // Langsung ubah style display
            }
        });
    });

    // --- TUTUP MODAL (BATAL) ---
    if (btnCancelForm) {
        btnCancelForm.addEventListener('click', () => studentModal.style.display = 'none');
    }
    if (btnCancelDelete) {
        btnCancelDelete.addEventListener('click', () => deleteModal.style.display = 'none');
    }
});
