const btnAbrir = document.getElementById('btnAbrir');
const fileInput = document.getElementById('fileInput');
const btnSubir = document.getElementById('btnSubir');

btnAbrir.addEventListener('click', () => {
  fileInput.click();
});

fileInput.addEventListener('change', () => {
  if (fileInput.files.length > 0) {
    const archivo = fileInput.files[0];
    btnAbrir.textContent = archivo.name;
    btnSubir.style.display = 'inline-block';
  }
});

/*---------------------------------------------------------------------------------------------*/

const btnComprimido = document.getElementById('btnComprimido');
const fileRAR = document.getElementById('fileRAR');
const btnDescomprimir = document.getElementById('btnDescomprimir');

btnComprimido.addEventListener('click', () => {
  fileRAR.click();
});

fileRAR.addEventListener('change', () => {
  if (fileRAR.files.length > 0) {
    const archivo = fileRAR.files[0];
    btnComprimido.textContent = archivo.name;
    btnDescomprimir.style.display = 'inline-block';
  }
});
