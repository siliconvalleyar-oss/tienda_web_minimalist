document.addEventListener('DOMContentLoaded', function () {

    // Hero carousel keyboard nav
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
            const prev = document.getElementById('anglePrev');
            if (prev) prev.click();
        } else if (e.key === 'ArrowRight') {
            const next = document.getElementById('angleNext');
            if (next) next.click();
        }
    });

    // AOS init (from WEB)
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 600, easing: 'ease-out', once: true, offset: 60 });
    }

    // Header scroll effect (from WEB)
    const header = document.getElementById('header');
    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        header.classList.toggle('scrolled', scrollTop > 50);
    });

    // Mobile menu toggle (from WEB)
    const menuToggle = document.getElementById('menuToggle');
    const navList = document.getElementById('navList');
    if (menuToggle && navList) {
        menuToggle.addEventListener('click', function () {
            navList.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navList.classList.remove('active');
                menuToggle.classList.remove('active');
            });
        });
    }

    // Back to top (from AXIS-MOBI)
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        backToTop.classList.toggle('show', scrollTop > 300);
    });
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Ripple effect on buttons (from WEB)
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });

});
