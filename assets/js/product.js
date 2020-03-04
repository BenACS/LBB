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
    changeImageDependingOnColor(color_selector);
    color_selector.addEventListener('change',checkArticle);
    color_selector.onchange = function() {
        changeImageDependingOnColor(color_selector);
    }
}

if (document.getElementById("size_selector")) {
    size_selector.addEventListener('change',checkArticle);
}

if (document.getElementById("device_selector")) {
    device_selector.addEventListener('change',checkArticle);
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

function checkArticle() {
    const params = new URLSearchParams();
        params.append('size',document.getElementById("size_selector") ? size_selector.value : null);
        params.append('color',document.getElementById("color_selector") ? color_selector.value : null);
        params.append('device',document.getElementById("device_selector") ? device_selector.value : null);

    const url = variation_form.action;
    axios.post(url, params)
        .then(function(response) { 
            console.log(response.data)
            js_stock.textContent = response.data.stock;
            js_articleId.value = response.data.articleId;
        })
}
