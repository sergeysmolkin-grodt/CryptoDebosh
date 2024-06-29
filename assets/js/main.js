/*
Template Name: CryptoLand - Crypto Currency Landing Page Template.
Author: GrayGrids
*/

import WOW from 'wowjs';

(function () {
    //===== Prealoder

    window.onload = function () {
        window.setTimeout(fadeout, 500);
    }

    function fadeout() {
        document.querySelector('.preloader').style.opacity = '0';
        document.querySelector('.preloader').style.display = 'none';
    }


    /*=====================================
    Sticky
    ======================================= */
    window.onscroll = function () {
        var header_navbar = document.querySelector(".navbar-area");
        var sticky = header_navbar.offsetTop;

        var logo = document.querySelector('.navbar-brand img')
        if (window.pageYOffset > sticky) {
          header_navbar.classList.add("sticky");
          logo.src = 'assets/images/logo/logo.svg';
        } else {
          header_navbar.classList.remove("sticky");
          logo.src = 'assets/images/logo/white-logo.svg';
        }

        // show or hide the back-top-top button
        var backToTo = document.querySelector(".scroll-top");
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            backToTo.style.display = "flex";
        } else {
            backToTo.style.display = "none";
        }
    };

    // WOW active
    new WOW.WOW().init();

    //===== mobile-menu-btn
    document.addEventListener('DOMContentLoaded', function () {
        let navbarToggler = document.querySelector(".mobile-menu-btn");
        if (navbarToggler) {
            navbarToggler.addEventListener('click', function () {
                navbarToggler.classList.toggle("active");
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        let navbarToggler = document.querySelector(".mobile-menu-btn");
        if (navbarToggler) {
            navbarToggler.addEventListener('click', function () {
                navbarToggler.classList.toggle("active");
            });
        }

        // Ensure fadeout is accessing an existing element
        let fadeoutElement = document.querySelector("#fadeout-element");
        if (fadeoutElement && fadeoutElement.style) {
            // Your fadeout logic here
        }
    });


})();