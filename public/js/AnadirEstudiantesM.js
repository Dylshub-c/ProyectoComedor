const btnAbrir = document.getElementById('btnAbrir');
const fileInput = document.getElementById('fileInput');

btnAbrir.addEventListener('click', () => {
fileInput.click();
});

fileInput.addEventListener('change', () => {
if (fileInput.files.length > 0) {
    const archivo = fileInput.files[0];
    btnAbrir.textContent = archivo.name;
}
});