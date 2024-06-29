import React, { useEffect } from 'react';
import ReactDOM from 'react-dom/client';
import WOW from 'wowjs';
import './css/bootstrap.min.css';
import './css/animate.css';
import './css/main.css';
import './css/glightbox.min.css';
import './css/tiny-slider.css';
import './css/LineIcons.3.0.css';


import './js/count-up.min.js';
import './js/tiny-slider.js';
import './js/glightbox.min.js';
import './js/main.js';

const App = () => {
    useEffect(() => {
        if (typeof window !== "undefined") {
            const wow = new WOW.WOW();
            wow.init();
        }

        // Инициализация других сторонних скриптов
        if (typeof tns === 'function') {
            tns({
                container: '.testimonial-slider',
                items: 3,
                slideBy: 'page',
                autoplay: false,
                mouseDrag: true,
                gutter: 0,
                nav: true,
                controls: false,
                responsive: {
                    0: {
                        items: 1,
                    },
                    540: {
                        items: 1,
                    },
                    768: {
                        items: 2,
                    },
                    992: {
                        items: 2,
                    },
                    1170: {
                        items: 3,
                    }
                }
            });
        }

        if (typeof GLightbox === 'function') {
            GLightbox({
                'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
                'type': 'video',
                'source': 'youtube', //vimeo, youtube or local
                'width': 900,
                'autoplayVideos': true,
            });
        }

        // Инициализация других скриптов, зависящих от DOM
        let navbarToggler = document.querySelector(".mobile-menu-btn");
        if (navbarToggler) {
            navbarToggler.addEventListener('click', function () {
                navbarToggler.classList.toggle("active");
            });
        }
    }, []);

    return (
        <div>
            <header className="header navbar-area">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-12">
                            <div className="nav-inner">
                                <nav className="navbar navbar-expand-lg">
                                    <a className="navbar-brand" href="index.html">
                                        <img src="/build/images/logo/white-logo.svg" alt="Logo" />
                                    </a>
                                    <button className="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                            aria-expanded="false" aria-label="Toggle navigation">
                                        <span className="toggler-icon"></span>
                                        <span className="toggler-icon"></span>
                                        <span className="toggler-icon"></span>
                                    </button>
                                    <div className="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                        <ul id="nav" className="navbar-nav ms-auto">
                                            <li className="nav-item">
                                                <a href="index.html" className="active" aria-label="Toggle navigation">Home</a>
                                            </li>
                                            <li className="nav-item">
                                                <a href="about-us.html" aria-label="Toggle navigation">About</a>
                                            </li>
                                            <li className="nav-item">
                                                <a className="dd-menu collapsed" href="javascript:void(0)" data-bs-toggle="collapse"
                                                   data-bs-target="#submenu-1-1" aria-controls="navbarSupportedContent"
                                                   aria-expanded="false" aria-label="Toggle navigation">Pages</a>
                                                <ul className="sub-menu collapse" id="submenu-1-1">
                                                    <li className="nav-item"><a href="about-us.html">About Us</a></li>
                                                    <li className="nav-item"><a href="signin.html">Sign In</a></li>
                                                    <li className="nav-item"><a href="signup.html">Sign Up</a></li>
                                                    <li className="nav-item"><a href="reset-password.html">Reset Password</a></li>
                                                    <li className="nav-item"><a href="mail-success.html">Mail Success</a></li>
                                                    <li className="nav-item"><a href="404.html">404 Error</a></li>
                                                </ul>
                                            </li>
                                            <li className="nav-item">
                                                <a className="dd-menu collapsed" href="javascript:void(0)" data-bs-toggle="collapse"
                                                   data-bs-target="#submenu-1-2" aria-controls="navbarSupportedContent"
                                                   aria-expanded="false" aria-label="Toggle navigation">Blog</a>
                                                <ul className="sub-menu collapse" id="submenu-1-2">
                                                    <li className="nav-item"><a href="blog-grid.html">Blog Grid</a></li>
                                                    <li className="nav-item"><a href="blog-single.html">Blog Single</a></li>
                                                </ul>
                                            </li>
                                            <li className="nav-item">
                                                <a href="contact.html" aria-label="Toggle navigation">Contact</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div className="button">
                                        <a href="signup.html" className="btn">Get started</a>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <section className="hero-area">
                <img className="hero-shape" src="/build/images/hero/hero-shape.svg" alt="#" />
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-5 col-md-12 col-12">
                            <div className="hero-content">
                                <h4 className="wow fadeInUp" data-wow-delay=".2s">Start Envesting & Earn Money</h4>
                                <h1 className="wow fadeInUp" data-wow-delay=".4s">Say goodbye to <br />idle
                                    <span>
                                        <img className="text-shape" src="/build/images/hero/text-shape.svg" alt="#" />
                                        money.
                                    </span>
                                </h1>
                                <p className="wow fadeInUp" data-wow-delay=".6s">Invest your spare change in Bitcoin and save with<br /> crypto to earn interest in real time.</p>
                                <div className="button wow fadeInUp" data-wow-delay=".8s">
                                    <a href="about-us.html" className="btn">Discover More</a>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-7 col-12">
                            <div className="hero-image">
                                <img className="main-image" src="/build/images/hero/home2-bg.png" alt="#" />
                                <img className="h2-move-1" src="/build/images/hero/h2-bit-l.png" alt="#" />
                                <img className="h2-move-2" src="/build/images/hero/h2-bit-m.png" alt="#" />
                                <img className="h2-move-3" src="/build/images/hero/h2-bit-s.png" alt="#" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
};

const rootElement = document.getElementById('root');
ReactDOM.createRoot(rootElement).render(<App />);
