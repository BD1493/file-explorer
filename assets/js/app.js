document.addEventListener('DOMContentLoaded', () => {
    console.log('File Explorer JS loaded');

    // Color-code file list
    document.querySelectorAll('ul li').forEach((li, index) => li.style.backgroundColor = `hsl(${index * 35 % 360},70%,90%)`);

    // Editor auto-save + live polling
    const editor = document.querySelector('textarea[name="content"]');
    if (editor) {
        let lastContent = editor.value;
        setInterval(async () => {
            const res = await fetch(window.location.href + '?ajax=1');
            const text = await res.text();
            if (text !== lastContent) { editor.value = text; lastContent = text; }
        }, 2000);
        editor.addEventListener('input', () => {
            lastContent = editor.value;
            fetch(window.location.href, { method: 'POST', body: new URLSearchParams({ content: editor.value }) }).catch(err => console.error(err));
        });
    }

    // Drag & drop upload
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', e => e.preventDefault());
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            Array.from(e.dataTransfer.files).forEach(file => {
                const fd = new FormData();
                fd.append('file', file);
                fetch('upload.php', { method: 'POST', body: fd }).then(() => location.reload());
            });
        });
    }

    // Modal for share/request
    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modal-body');
    const modalClose = document.getElementById('modal-close');
    function openModal(html) { modalContent.innerHTML = html; modal.classList.remove('hidden'); }
    modalClose.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const li = e.target.closest('li');
            openModal(`<iframe src="share.php?id=${li.dataset.fileId}" style="width:100%;height:300px;border:none;"></iframe>`);
        });
    });
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const li = e.target.closest('li');
            window.location.href = `edit.php?id=${li.dataset.fileId}`;
        });
    });
});
