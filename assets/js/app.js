/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import '../css/app.scss';

const feather = require('feather-icons');

feather.replace();

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import 'popper.js';
// import 'bootstrap';
// import 'bootstrap/js/dist/util';
// import 'bootstrap/js/dist/collapse';
import $ from 'jquery';

// console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

// Horizontal menu in mobile
$('[data-toggle="horizontal-menu-toggle"]').on("click", function() {
    $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
});
// Horizontal menu navigation in mobile menu on click
var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
navItemClicked.on("click", function(event) {
    if(window.matchMedia('(max-width: 991px)').matches) {
        console.log($(this));
        if(!($(this).hasClass('show-submenu'))) {
            navItemClicked.removeClass('show-submenu');
        }
        $(this).toggleClass('show-submenu');
    }
})

$(window).scroll(function() {
    if(window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        if ($(window).scrollTop() >= 60) {
            $(header).addClass('fixed-on-scroll');
        } else {
            $(header).removeClass('fixed-on-scroll');
        }
    }
});
