/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.

import $ from 'jquery';
import greet from './greet';
import icons from 'glyphicons';

document.addEventListener('DOMContentLoaded', function() {
    var userRating = document.querySelector('.js-user-rating');
    var isAuthenticated = userRating.dataset.isAuthenticated;

    console.log(isAuthenticated);

    // or with jQuery
    //var isAuthenticated = $('.js-user-rating').data('isAuthenticated');
});

console.log('IO');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$(document).ready(function() {
    $('body').prepend('<h1>'+greet('Michał')+'</h1>');
});
