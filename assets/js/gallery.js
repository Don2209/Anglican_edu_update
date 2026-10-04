/**
 * Homepage gallery: category filters + lightbox.
 * Uses the View Transitions API (where supported) so the grid morphs between
 * filters and a tile grows into the lightbox. Everything still works without
 * it, and without JS each tile is a plain link to the full-size image.
 */
(function () {
    'use strict';

    var root = document.querySelector('#gallery');
    if (!root) return;

    var items = Array.prototype.slice.call(root.querySelectorAll('[data-gallery-item]'));
    var filterBar = root.querySelector('[data-gallery-filters]');
    var status = root.querySelector('[data-gallery-status]');
    var dialog = root.querySelector('[data-lightbox]');
    var lbImg = root.querySelector('[data-lightbox-img]');
    var lbTag = root.querySelector('[data-lightbox-tag]');
    var lbText = root.querySelector('[data-lightbox-text]');
    var lbCount = root.querySelector('[data-lightbox-count]');

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var canMorph = typeof document.startViewTransition === 'function' && !reduceMotion;

    /** Run a DOM update inside a view transition when possible. */
    function transition(update) {
        if (!canMorph) {
            update();
            return Promise.resolve();
        }
        return document.startViewTransition(update).finished.catch(function () {});
    }

    /* --- Filters --------------------------------------------------------- */

    filterBar.hidden = false;

    filterBar.addEventListener('click', function (event) {
        var button = event.target.closest('[data-filter]');
        if (!button || button.getAttribute('aria-pressed') === 'true') return;

        var filter = button.getAttribute('data-filter');
        filterBar.querySelectorAll('[data-filter]').forEach(function (b) {
            b.setAttribute('aria-pressed', String(b === button));
        });

        // Give each tile a name so the browser can morph it to its new spot
        items.forEach(function (item, i) {
            item.style.viewTransitionName = 'gallery-item-' + i;
            item.classList.add('is-visible'); // skip the scroll reveal once filtering
        });

        transition(function () {
            items.forEach(function (item) {
                item.hidden = filter !== 'all' && item.getAttribute('data-category') !== filter;
            });
        }).then(function () {
            items.forEach(function (item) { item.style.viewTransitionName = ''; });
        });

        var shown = visibleItems().length;
        status.textContent = 'Showing ' + shown + (filter === 'all' ? '' : ' ' + button.firstChild.textContent.trim()) + ' photo' + (shown === 1 ? '' : 's');
    });

    function visibleItems() {
        return items.filter(function (item) { return !item.hidden; });
    }

    /* --- Lightbox -------------------------------------------------------- */

    var current = 0;      // index within the currently visible tiles
    var opener = null;    // tile that opened the lightbox, to return focus

    function tileAt(index) {
        return visibleItems()[index].querySelector('[data-gallery-open]');
    }

    function show(index) {
        var list = visibleItems();
        current = (index + list.length) % list.length;
        var tile = tileAt(current);
        var thumb = tile.querySelector('img');

        // Show the already-loaded thumbnail instantly, then swap in the large file
        lbImg.src = thumb.currentSrc || thumb.src;
        lbImg.alt = thumb.alt;
        lbImg.width = parseInt(tile.getAttribute('data-full-w'), 10);
        lbImg.height = parseInt(tile.getAttribute('data-full-h'), 10);
        var full = new Image();
        full.onload = function () { if (tileAt(current) === tile) lbImg.src = full.src; };
        full.src = tile.getAttribute('data-full');

        lbTag.textContent = tile.getAttribute('data-category-label');
        lbText.textContent = tile.getAttribute('data-caption');
        lbCount.textContent = pad(current + 1) + ' / ' + pad(list.length);
    }

    function pad(n) { return (n < 10 ? '0' : '') + n; }

    function open(index) {
        var thumb = tileAt(index).querySelector('img');
        thumb.style.viewTransitionName = 'gallery-photo';
        document.documentElement.classList.add('has-lightbox');

        transition(function () {
            thumb.style.viewTransitionName = '';
            show(index);
            lbImg.style.viewTransitionName = 'gallery-photo';
            dialog.showModal();
        }).then(function () { lbImg.style.viewTransitionName = ''; });
    }

    function close() {
        var thumb = tileAt(current).querySelector('img');
        lbImg.style.viewTransitionName = 'gallery-photo';

        transition(function () {
            lbImg.style.viewTransitionName = '';
            dialog.close();
            thumb.style.viewTransitionName = 'gallery-photo';
        }).then(function () {
            thumb.style.viewTransitionName = '';
            document.documentElement.classList.remove('has-lightbox');
            if (opener) opener.focus();
        });
    }

    function step(direction) {
        show(current + direction);
        if (lbImg.animate && !reduceMotion) {
            lbImg.animate(
                [{ opacity: 0, transform: 'translateX(' + direction * 24 + 'px) scale(0.98)' }, { opacity: 1, transform: 'none' }],
                { duration: 380, easing: 'cubic-bezier(0.22, 0.61, 0.36, 1)' }
            );
        }
    }

    root.addEventListener('click', function (event) {
        var tile = event.target.closest('[data-gallery-open]');
        if (!tile) return;
        event.preventDefault();
        opener = tile;
        open(visibleItems().indexOf(tile.closest('[data-gallery-item]')));
    });

    root.querySelector('[data-lightbox-close]').addEventListener('click', close);
    root.querySelector('[data-lightbox-prev]').addEventListener('click', function () { step(-1); });
    root.querySelector('[data-lightbox-next]').addEventListener('click', function () { step(1); });

    // Esc: run our animated close instead of the instant native one
    dialog.addEventListener('cancel', function (event) {
        event.preventDefault();
        close();
    });

    dialog.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowRight') step(1);
        if (event.key === 'ArrowLeft') step(-1);
    });

    // Click on the dark backdrop (outside the photo) closes
    dialog.addEventListener('click', function (event) {
        if (event.target === dialog) close();
    });

    // Swipe left/right between photos
    var startX = null;
    dialog.addEventListener('touchstart', function (event) { startX = event.touches[0].clientX; }, { passive: true });
    dialog.addEventListener('touchend', function (event) {
        if (startX === null) return;
        var dx = event.changedTouches[0].clientX - startX;
        startX = null;
        if (Math.abs(dx) > 50) step(dx < 0 ? 1 : -1);
    }, { passive: true });
})();
