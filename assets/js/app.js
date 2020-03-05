/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import "bootstrap";

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


let displayCat = document.getElementsByClassName("categories");
let displaySubCat = document.getElementsByClassName("subCategories");

for (let i = 0; i < displayCat.length; i++) {
    displayCat[i].onmouseover = function () {
        for (let j = 0; j < displaySubCat.length; j++) {
            displaySubCat[j].classList.add('disNone');
        }
        displaySubCat[i].classList.remove('disNone');
    }
    displaySubCat[i].onmouseover = function () {
        displaySubCat[i].classList.remove('disNone');
    }
    displaySubCat[i].onmouseout = function () {
        displaySubCat[i].classList.add('disNone');
    }
}

export function addToastCart(src, title, quantity) {
    let divToast = document.createElement('DIV');
    divToast.setAttribute('class', 'toast col-12 m-1 p-0 fadeIn show');
    divToast.setAttribute('role', 'alert');
    divToast.dataset.autohide = 'false';
    divToast.innerHTML = `<div class="toast-header p-0"><strong class="mr-auto ml-2 text-success">Product added into the cart <i class="fas fa-check"></i></strong><button type="button" class="mx-2 mb-1 close" data-dismiss="toast"><span>&times;</span></button></div><div class="toast-body row align-items-center">
        <div class="col-3"><img src="${ src}" class="w-100"></div><div class="col-9"><b>${title}</b><br>x${quantity}</div></div>`

    divToast.firstChild.lastChild.onclick = function () {
        removeToastCart(divToast)
    }
    toast_box.insertBefore(divToast, toast_box.firstChild);
    setTimeout(function () { removeToastCart(divToast); }, 8000);
}

function removeToastCart(toast) {
    // toast.classList.replace('show', 'fade');
    toast.classList.replace('fadeIn', 'fadeOut');
    setTimeout(function () { toast.remove() }, 500);
}