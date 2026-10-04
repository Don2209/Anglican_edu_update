/**
 * "Discover More" carousel.
 * The active progress bar's CSS animation is the autoplay timer: when it ends
 * we advance. Pausing just sets animation-play-state, so the bar freezes
 * exactly where it is and resumes from there.
 *
 * The loop is endless: cards are re-ordered (CSS `order`) so the open card
 * always sits in the middle slot, and a FLIP animation glides each card from
 * where it was to where it now is.
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-discover]');
    if (!root) return;

    var viewport = root.querySelector('[data-discover-viewport]');
    var track = root.querySelector('[data-discover-track]');
    var items = root.querySelectorAll('[data-discover-item]');
    var bars = root.querySelectorAll('[data-discover-bar]');
    var pauseBtn = root.querySelector('[data-discover-pause]');
    var count = items.length;
    if (!count) return;

    var current = 0;
    var mid = Math.floor(count / 2);   // slot the open card always occupies
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var userPaused = reduceMotion;
    var hoverPaused = false;
    var offscreen = true;

    root.style.setProperty('--discover-interval', (parseInt(root.getAttribute('data-interval'), 10) || 7000) + 'ms');
    root.querySelector('[data-discover-ui]').hidden = false;

    /** Read a length custom property (px or vw) as pixels. */
    function cssPx(name) {
        var value = getComputedStyle(root).getPropertyValue(name).trim();
        var number = parseFloat(value) || 0;
        return /vw$/.test(value) ? number * window.innerWidth / 100 : number;
    }

    /** Position the track so the middle slot (the open card) is centred. */
    function centre() {
        var stacked = cssPx('--stacked') === 1;
        var gap = cssPx('--gap');
        var inactiveWidth = cssPx('--photo-w');
        var activeWidth = stacked
            ? cssPx('--photo-w-active')
            : cssPx('--panel-w') + cssPx('--card-gap') + cssPx('--photo-w-active');
        var activeCentre = mid * (inactiveWidth + gap) + activeWidth / 2;
        track.style.transform = 'translateX(' + (viewport.clientWidth / 2 - activeCentre) + 'px)';
    }

    function go(index, animate) {
        current = (index + count) % count;
        animate = animate !== false && !reduceMotion;

        // FLIP, step 1: remember where every card is now
        var before = [];
        items.forEach(function (item, i) { before[i] = item.getBoundingClientRect().left; });

        items.forEach(function (item, i) {
            item.style.order = (i - current + mid + count) % count;
            var active = i === current;
            var panel = item.querySelector('[data-discover-panel]');
            var photo = item.querySelector('[data-discover-select]');
            item.classList.toggle('is-active', active);
            panel.toggleAttribute('inert', !active);
            if (active) panel.removeAttribute('aria-hidden');
            else panel.setAttribute('aria-hidden', 'true');
            // The open card's photo is not a control; the others select their card
            photo.tabIndex = active ? -1 : 0;
        });

        bars.forEach(function (bar, i) {
            bar.classList.remove('is-active');
            bar.classList.toggle('is-done', i < current);
            if (i === current) bar.setAttribute('aria-current', 'true');
            else bar.removeAttribute('aria-current');
        });
        // Restart the timer animation on the new active bar
        void bars[current].offsetWidth;
        bars[current].classList.add('is-active');

        centre();

        // FLIP, steps 2-4: measure the new spots and animate from the old ones
        if (!animate) return;
        items.forEach(function (item, i) {
            var dx = before[i] - item.getBoundingClientRect().left;
            if (!dx || !item.animate) return;
            if (Math.abs(dx) > viewport.clientWidth * 0.6) {
                // wrapped from one end to the other: fade in instead of flying across
                item.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 600, easing: 'ease' });
            } else {
                item.animate([{ transform: 'translateX(' + dx + 'px)' }, { transform: 'none' }],
                    { duration: 700, easing: 'cubic-bezier(0.22, 0.61, 0.36, 1)' });
            }
        });
    }

    function syncPaused() {
        root.classList.toggle('is-paused', userPaused || hoverPaused || offscreen);
    }

    function setUserPaused(paused) {
        userPaused = paused;
        pauseBtn.setAttribute('aria-pressed', String(paused));
        pauseBtn.querySelector('.visually-hidden').textContent = paused ? 'Play' : 'Pause';
        syncPaused();
    }

    // Timer finished -> next card
    root.addEventListener('animationend', function (event) {
        if (event.animationName === 'discover-progress' && event.target.parentElement === bars[current]) {
            go(current + 1);
        }
    });

    bars.forEach(function (bar) {
        bar.addEventListener('click', function () { go(parseInt(bar.getAttribute('data-discover-bar'), 10)); });
    });

    root.querySelectorAll('[data-discover-select]').forEach(function (photo) {
        photo.addEventListener('click', function () {
            var index = parseInt(photo.getAttribute('data-discover-select'), 10);
            if (index !== current) go(index);
        });
    });

    root.querySelector('[data-discover-prev]').addEventListener('click', function () { go(current - 1); });
    root.querySelector('[data-discover-next]').addEventListener('click', function () { go(current + 1); });
    pauseBtn.addEventListener('click', function () { setUserPaused(!userPaused); });

    // Arrow keys while focus is inside the carousel
    root.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowRight') { go(current + 1); event.preventDefault(); }
        if (event.key === 'ArrowLeft') { go(current - 1); event.preventDefault(); }
    });

    // Swipe: only a clearly sideways gesture changes card, so scrolling the
    // page up or down over the carousel never flips it by accident.
    var start = null;
    var touchTimer;
    viewport.addEventListener('touchstart', function (event) {
        start = { x: event.touches[0].clientX, y: event.touches[0].clientY };
        // Touching the carousel pauses it, like hovering does with a mouse
        window.clearTimeout(touchTimer);
        hoverPaused = true;
        syncPaused();
    }, { passive: true });
    viewport.addEventListener('touchend', function (event) {
        if (start) {
            var dx = event.changedTouches[0].clientX - start.x;
            var dy = event.changedTouches[0].clientY - start.y;
            if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) go(current + (dx < 0 ? 1 : -1));
        }
        start = null;
        // Resume a few seconds after the finger lifts
        touchTimer = window.setTimeout(function () { hoverPaused = false; syncPaused(); }, 5000);
    }, { passive: true });

    // Pause while being read or interacted with, and while off screen
    viewport.addEventListener('mouseenter', function () { hoverPaused = true; syncPaused(); });
    viewport.addEventListener('mouseleave', function () { hoverPaused = false; syncPaused(); });
    root.addEventListener('focusin', function () { hoverPaused = true; syncPaused(); });
    root.addEventListener('focusout', function (event) {
        if (!root.contains(event.relatedTarget)) { hoverPaused = false; syncPaused(); }
    });

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            offscreen = !entries[0].isIntersecting;
            syncPaused();
        }, { threshold: 0.6 }).observe(viewport);   // autoplay only while mostly in view
    } else {
        offscreen = false;
    }

    var resizeTimer;
    window.addEventListener('resize', function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(centre, 100);
    });

    setUserPaused(userPaused);
    go(0, false);
})();
