let thumbnails = document.getElementsByClassName("thumbnail-images");


for (let i = 0; i < thumbnails.length; i++) {
    thumbnails[i].onmouseover = function () {
        for (thumbnail of thumbnails) {
            thumbnail.classList.remove('border-secondary');
        }
        thumbnails[i].classList.add('border-secondary');
        main_image.src = thumbnails[i].src;
    }
}

if (document.getElementById("color_selector")) {
    for (let i = 0; i < color_selector.length; i++) {
        changeImageDependingOnColor(color_selector[i]);
        color_selector[i].onclick = function () {
            changeImageDependingOnColor(color_selector[i]);
        }
    }
}


function changeImageDependingOnColor(element) {
    for (thumbnail of thumbnails) {
        thumbnail.classList.remove('border-secondary');
        if (thumbnail.src.includes(element.value.toLowerCase())) {
            main_image.src = thumbnail.src;
            thumbnail.classList.add('border-secondary');
        }
    }
}
