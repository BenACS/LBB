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
            js_stock_message.innerHTML = response.data.stockMessage;
            quantity_selector.dataset.articleId = response.data.articleId;
            quantity_selector.dataset.stock = response.data.stock;
            changeQuantitySelector();
        })
}

function changeQuantitySelector() {
    let n = parseInt(quantity_selector.dataset.stock);
    if (n == 0) {
        cart_form.style.display = "none";
    } else {
        cart_form.style.display = "block";
        quantity_selector.innerHTML = '';
        for (let i = 1 ; i<=n ; i++) {
            if (i <=5 ) {
                let option = document.createElement("OPTION");
                option.innerText = i;
                quantity_selector.appendChild(option);
            } else {
                break;
            }
        }
    }
}

console.log("ceci est un test");

window.onload = function() {
    checkArticle();
}