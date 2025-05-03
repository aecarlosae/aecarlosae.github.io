/*!
* Start Bootstrap - Resume v7.0.6 (https://startbootstrap.com/theme/resume)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-resume/blob/master/LICENSE)
*/

window.addEventListener('DOMContentLoaded', event => {

    // Activate Bootstrap scrollspy on the main nav element
    const sideNav = document.body.querySelector('#sideNav');
    if (sideNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#sideNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);


            const urlParams = new URLSearchParams(window.location.search);
            const notificationMessage = document.createElement('div');
            notificationMessage.role = 'alert';

            if (urlParams.has('success') && urlParams.get('success') == 1) {
                notificationMessage.className = 'alert alert-success mt-3';
                notificationMessage.textContent = form.dataset.successMessage;
                form.insertBefore(notificationMessage, form.firstChild);
            }

            console.log(form.dataset);

            if (urlParams.has('error') && urlParams.get('error') == '500') {
                notificationMessage.className = 'alert alert-danger mt-3';
                notificationMessage.textContent = form.dataset.errorRecaptchaMessage;
                form.insertBefore(notificationMessage, form.firstChild);
            } else if (urlParams.has('error') && urlParams.get('error') != '500') {
                notificationMessage.className = 'alert alert-warning mt-3';
                notificationMessage.textContent = form.dataset.errorMessage;
                form.insertBefore(notificationMessage, form.firstChild);
            }
        });
    })();
});
