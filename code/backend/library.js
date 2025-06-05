document.getElementById('upload-btn').addEventListener('click', () => {
  document.getElementById('upload-modal').classList.remove('hidden');
});

document.getElementById('close-modal').addEventListener('click', () => {
  document.getElementById('upload-modal').classList.add('hidden');
});

document.getElementById('upload-area').addEventListener('click', () => {
  document.getElementById('file-input').click();
});

document.getElementById('confirm-upload').addEventListener('click', () => {
  alert("Mock upload complete. Backend integration folgt später.");
  document.getElementById('upload-modal').classList.add('hidden');
});

document.querySelectorAll('.folder').forEach(folder => {
  folder.addEventListener('click', () => {
    document.querySelectorAll('.folder').forEach(f => f.classList.remove('active'));
    folder.classList.add('active');
    // Hier später: Dateien des jeweiligen Folders laden
  });
});

document.getElementById('new-folder-btn').addEventListener('click', () => {
  const folderName = prompt("Folder name:");
  if (folderName) {
    const li = document.createElement('li');
    li.textContent = folderName;
    li.className = 'folder';
    document.querySelector('.folder-list').appendChild(li);
    li.addEventListener('click', () => {
      document.querySelectorAll('.folder').forEach(f => f.classList.remove('active'));
      li.classList.add('active');
    });
  }
});
