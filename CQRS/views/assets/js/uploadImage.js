
let uploadBtn = document.getElementById('upload');
let clearBtn = document.getElementById('clear');
let noImage = document.getElementById('noImage');
let fileInput = document.getElementById('exampleFormControlFile1');
let imagePreview = document.querySelector('.image-preview');

fileInput.addEventListener('change', (e) => {
  let allowedExt = ['image/png', 'image/jpg', 'image/jpeg'];

  let image = e.target.files[0];

  if (image && allowedExt.includes(image.type)) {
    let tmpImageUrl = URL.createObjectURL(image);
    imagePreview.innerHTML += `<img src="${tmpImageUrl}" class="image-p" alt="preview">`;

    noImage.hidden = true;
    uploadBtn.disabled = false;
    clearBtn.disabled = false;
  } else {
    uploadBtn.disabled = true;
    clearBtn.disabled = true;
    noImage.hidden = false;
  }
});

clearBtn.addEventListener('click', () => {
  fileInput.value = '';
  imagePreview.innerHTML = '';
  noImage.hidden = false;
  uploadBtn.disabled = true;
  clearBtn.disabled = true;
});

let uploadButtons = document.querySelectorAll('button[data-target="#staticBackdrop"]');
let bookId;

uploadButtons.forEach( (button) => {
  button.addEventListener('click',  () =>{
    bookId = button.getAttribute('data-book-id');
  });
});

uploadBtn.addEventListener('click', () => {
  let file = fileInput.files[0];

  let formData = new FormData();
  formData.append('image', file);
  formData.append('bookId', bookId);

  let xhr = new XMLHttpRequest();

  xhr.open('POST', '/admin/book/unready');
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE){
      if (xhr.status === 200){
        //
      }
    }
  }
  xhr.send(formData);
});