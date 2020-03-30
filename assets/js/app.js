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

/* NAVBAR CATEGORIES */

let displayCat = document.getElementsByClassName("categories");
let displaySubCat = document.getElementsByClassName("subCategories");
const navMainCat = document.getElementById("nav_mainCat");

// Get the window's width
let viewerWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

// If the width is greater than mobile screens sizes (this is similar to media queries)
// Issue with this : you have to reload the page to see the changes on pc
if(viewerWidth > 575){
    for (let i = 0; i < displayCat.length; i++) {
        displayCat[i].onmouseover = function () {
            for (let j = 0; j < displaySubCat.length; j++) {
                displaySubCat[j].classList.add('disNone');
            }
            if (!(displaySubCat[i].classList.contains("exception"))) {
                displaySubCat[i].classList.remove('disNone');
            }

        }
        displaySubCat[i].onmouseover = function () {
            displaySubCat[i].classList.remove('disNone');
        }
        displaySubCat[i].onmouseout = function () {
            displaySubCat[i].classList.add('disNone');
        }
    }
} else {
    navMainCat.classList.add('collapse');
}

/* TOAST CART */

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

/* SEARCH BAR AUTOCOMPLETION */

function autocomplete(inp, str) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    let arr = str.split(",")
    let currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        let a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false; }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            // console.log(val.toUpperCase())

            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/

                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";

                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });

                a.appendChild(b);
                if (a.childElementCount > 3) {
                    break
                }
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13 && document.getElementById(this.id + "autocomplete-list")) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (let i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        let x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });

}

if (document.querySelector('#js-tagNames')) {
    let jsTagNames = document.querySelector('#js-tagNames');
    let jsonNames = jsTagNames.dataset.tagNames;
    autocomplete(document.getElementById("searchTag"), jsonNames);
}

/* UISLIDER TEST */

import noUiSlider from 'nouislider'
import 'nouislider/distribute/nouislider.css'

var slider = document.getElementById('priceslider');

if(slider){
    // Get min & max input fields
    const min = document.getElementById('min');
    const max = document.getElementById('max');

    const minValue = Math.floor(parseInt(slider.dataset.min, 10) / 10) * 10;
    const maxValue = Math.ceil(parseInt(slider.dataset.max, 10) / 10) * 10;

    const range = noUiSlider.create(slider, {
        start: [min.value || minValue, max.value || maxValue],
        connect: true,
        step: 5,
        range: {
            'min': minValue,
            'max': maxValue
        }
    });

    // When using the slider, update the input fields values
    range.on('slide', function(values, handle){
        if(handle === 0){
            min.value = Math.round(values[0]);
        }

        if(handle === 1){
            max.value = Math.round(values[1]);
        }
    });
}