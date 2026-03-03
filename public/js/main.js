// Pizz_a64 — Main JavaScript

// ── Header scroll effect ──────────────────────────────────────────────────
window.addEventListener('scroll', function () {
    const header = document.getElementById('header');
    if (header) {
        header.classList.toggle('scrolled', window.scrollY > 50);
    }
});

// ── Mobile hamburger menu ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('hamburger');
    const navMenu   = document.getElementById('navMenu');

    if (hamburger && navMenu) {
        // Clone nav into mobile nav
        let mobileNav = document.getElementById('mobileNav');
        if (!mobileNav) {
            mobileNav = document.createElement('div');
            mobileNav.id = 'mobileNav';
            mobileNav.className = 'mobile-nav';
            const ul = document.createElement('ul');
            navMenu.querySelectorAll('a').forEach(a => {
                const li = document.createElement('li');
                li.innerHTML = a.outerHTML;
                ul.appendChild(li);
            });
            mobileNav.appendChild(ul);
            document.querySelector('.navbar').style.position = 'relative';
            document.querySelector('.navbar').appendChild(mobileNav);
        }

        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
                hamburger.classList.remove('open');
                mobileNav.classList.remove('open');
            }
        });
    }

    // ── Size selection visual ──────────────────────────────────────────────
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(opt => {
        opt.addEventListener('click', function () {
            sizeOptions.forEach(o => o.style.borderColor = '');
            this.style.borderColor = 'var(--primary)';
        });
    });
});