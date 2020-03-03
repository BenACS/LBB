let thumbnails = document.getElementsByClassName("thumbnail-images");

for (let i=0 ; i<thumbnails.length ; i++) {
    thumbnails[i].onmouseover = function() {
        thumbnails[i].classList.add('border-secondary');
        main_image.src = thumbnails[i].src;
    }
    thumbnails[i].onmouseout = function() {
        thumbnails[i].classList.remove('border-secondary');
    }
}

console.log("hello");