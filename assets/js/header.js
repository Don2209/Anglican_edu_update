/**
 * Inner-page header: hides on scroll down, returns on scroll up, gains a
 * solid background once the page has scrolled, and runs the mobile menu.
 */
(function () {
    'use strict';

    var header = document.querySelector('[data-site-header]');
    if (!header) return;

    var toggle = header.querySelector('[data-header-toggle]');
    var menu = header.querySelector('[data-header-menu]');
    var label = toggle.querySelector('.visually-hidden');
    var lastY = window.scrollY;
    var ticking = false;

    function update() {
        ticking = false;
        var y = window.scrollY;
        header.classList.toggle('is-scrolled', y > 24);
        // Only tuck away once past the top, and never while the menu is open
        var hide = y > lastY && y > 160 && menu.hidden && !header.contains(document.activeElement);
        header.classList.toggle('is-hidden', hide);
        lastY = y;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }, { passive: true });
    update();

    function setOpen(open) {
        menu.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        label.textContent = open ? 'Close menu' : 'Open menu';
        header.classList.toggle('is-open', open);
        // A tucked-away (transformed) header would carry the fixed menu up with it
        if (open) header.classList.remove('is-hidden');
    }

    toggle.addEventListener('click', function () { setOpen(menu.hidden); });

    // Close after choosing a link (same-page anchors would otherwise leave it open)
    menu.addEventListener('click', function (event) {
        if (event.target.closest('a')) setOpen(false);
    });

    // Click outside the open menu closes it (as on the homepage)
    document.addEventListener('click', function (event) {
        if (!menu.hidden && !menu.contains(event.target) && !toggle.contains(event.target)) setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !menu.hidden) {
            setOpen(false);
            toggle.focus();
        }
    });
})();
