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

for (let i=0 ; i < displayCat.length ; i++) {
    displayCat[i].onmouseover = function () {
        for (let j=0 ; j<displaySubCat.length ; j++) {
            displaySubCat[j].classList.add('disNone');
        }
        displaySubCat[i].classList.remove('disNone');
    }
    displaySubCat[i].onmouseover = function() {
        displaySubCat[i].classList.remove('disNone');
    }
    displaySubCat[i].onmouseout = function() {
        displaySubCat[i].classList.add('disNone');
    }
}