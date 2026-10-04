/**
 * Institutions page: level filter + search, scroll-spy index, photo galleries,
 * the hero expanding gallery and the sortable comparison table.
 * Everything is enhancement: without JS all schools and photos are shown.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    /* --- Filter + search ------------------------------------------------- */

    var toolbar = document.querySelector('[data-toolbar]');
    var profiles = Array.prototype.slice.call(document.querySelectorAll('[data-profile]'));
    var indexItems = document.querySelectorAll('[data-index-item]');
    var countEl = document.querySelector('[data-count]');
    var emptyEl = document.querySelector('[data-empty]');
    var index = document.querySelector('[data-index]');

    if (toolbar) {
        var search = toolbar.querySelector('[data-search]');
        var level = 'all';
        toolbar.hidden = false;
        if (index) index.hidden = false;

        function apply() {
            var query = search.value.trim().toLowerCase();
            var shown = 0;
            profiles.forEach(function (profile) {
                var visible = (level === 'all' || profile.getAttribute('data-level') === level)
                    && (!query || profile.getAttribute('data-search').indexOf(query) !== -1);
                profile.hidden = !visible;
                if (visible) {
                    shown++;
                    profile.classList.add('is-visible');   // skip the scroll reveal once filtering
                }
            });
            indexItems.forEach(function (item) {
                var profile = document.getElementById(item.getAttribute('data-index-item'));
                item.hidden = profile.hidden;
            });
            countEl.textContent = shown + (shown === 1 ? ' school' : ' schools');
            emptyEl.hidden = shown !== 0;
            spy();
        }

        toolbar.addEventListener('click', function (event) {
            var button = event.target.closest('[data-filter]');
            if (!button) return;
            level = button.getAttribute('data-filter');
            toolbar.querySelectorAll('[data-filter]').forEach(function (b) {
                b.setAttribute('aria-pressed', String(b === button));
            });
            apply();
        });
        search.addEventListener('input', apply);
    }

    /* --- Scroll-spy index ------------------------------------------------ */

    var spyTicking = false;
    function spy() {
        if (!index) return;
        var line = window.innerHeight * 0.35;
        var current = null;
        profiles.forEach(function (profile) {
            if (!profile.hidden && profile.getBoundingClientRect().top < line) current = profile.id;
        });
        if (!current) {
            var first = profiles.filter(function (p) { return !p.hidden; })[0];
            current = first ? first.id : null;
        }
        indexItems.forEach(function (item) {
            item.classList.toggle('is-active', item.getAttribute('data-index-item') === current);
        });
    }
    window.addEventListener('scroll', function () {
        if (spyTicking) return;
        spyTicking = true;
        window.requestAnimationFrame(function () { spyTicking = false; spy(); });
    }, { passive: true });
    spy();

    /* --- Photo galleries ------------------------------------------------- */

    document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
        var photos = gallery.querySelectorAll('[data-photo]');
        var thumbs = gallery.querySelectorAll('[data-thumb]');
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var i = parseInt(thumb.getAttribute('data-thumb'), 10);
                photos.forEach(function (p, n) { p.classList.toggle('is-active', n === i); });
                thumbs.forEach(function (t, n) {
                    t.classList.toggle('is-active', n === i);
                    t.setAttribute('aria-pressed', String(n === i));
                });
            });
        });
    });

    /* --- Hero expanding gallery ----------------------------------------- */

    var slatsEl = document.querySelector('[data-slats]');
    if (slatsEl) {
        var slats = Array.prototype.slice.call(slatsEl.querySelectorAll('[data-slat]'));
        var slatStatus = slatsEl.querySelector('[data-slats-status]');
        var slatInterval = parseInt(slatsEl.getAttribute('data-interval'), 10) || 4500;
        var active = 0;
        var slatElapsed = 0;
        var slatLast = 0;
        var slatHover = false;
        var slatOnScreen = true;
        var slatTick = '';

        function open(index, announce) {
            active = (index + slats.length) % slats.length;
            slatElapsed = 0;
            slats.forEach(function (slat, i) {
                var on = i === active;
                slat.classList.toggle('is-active', on);
                slat.querySelector('[data-slat-trigger]').setAttribute('aria-expanded', String(on));
                slat.querySelector('.slat__link').tabIndex = on ? 0 : -1;
                if (on) {
                    var img = slat.querySelector('img');
                    if (img && img.loading === 'lazy') img.loading = 'eager';
                }
            });
            if (announce) slatStatus.textContent = slats[active].querySelector('.slat__name').textContent;
        }

        slats.forEach(function (slat, i) {
            var trigger = slat.querySelector('[data-slat-trigger]');
            trigger.addEventListener('click', function () { open(i, true); });
            // Mouse hover opens a panel (with a short delay so sweeping across doesn't flicker)
            var hoverTimer;
            slat.addEventListener('pointerenter', function (e) {
                if (e.pointerType !== 'mouse') return;
                hoverTimer = window.setTimeout(function () { if (i !== active) open(i, false); }, 140);
            });
            slat.addEventListener('pointerleave', function () { window.clearTimeout(hoverTimer); });
        });

        slatsEl.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                open(active + 1, true);
                slats[active].querySelector('.slat__link').focus();
            }
            if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                open(active - 1, true);
                slats[active].querySelector('.slat__link').focus();
            }
        });

        // Autoplay: pauses while hovered, focused, off screen or in a hidden tab
        slatsEl.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') slatHover = true; });
        slatsEl.addEventListener('pointerleave', function () { slatHover = false; });
        slatsEl.addEventListener('focusin', function () { slatHover = true; });
        slatsEl.addEventListener('focusout', function (e) { if (!slatsEl.contains(e.relatedTarget)) slatHover = false; });
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { slatOnScreen = entries[0].isIntersecting; }).observe(slatsEl);
        }

        function slatLoop(now) {
            if (!reduceMotion && !slatHover && slatOnScreen && !document.hidden) {
                slatElapsed += slatLast ? now - slatLast : 0;
                if (slatElapsed >= slatInterval) open(active + 1, false);
            }
            slatLast = now;
            var value = Math.min(slatElapsed / slatInterval, 1).toFixed(3);
            if (value !== slatTick) {
                slatTick = value;
                slats[active].querySelector('.slat__progress span').style.setProperty('--tick', value);
            }
            window.requestAnimationFrame(slatLoop);
        }

        open(0, false);
        // start counting once the entrance has played
        window.setTimeout(function () { window.requestAnimationFrame(slatLoop); }, 1600);
    }

    /* --- Sortable comparison table --------------------------------------- */

    var table = document.querySelector('[data-sortable]');
    if (table) {
        var tbody = table.querySelector('tbody');
        var originalOrder = Array.prototype.slice.call(tbody.rows);

        table.querySelectorAll('[data-sort]').forEach(function (button) {
            button.addEventListener('click', function () {
                var key = button.getAttribute('data-sort');
                var th = button.closest('th');
                var next = th.getAttribute('aria-sort') === 'descending' ? 'ascending'
                    : th.getAttribute('aria-sort') === 'ascending' ? 'none' : 'descending';

                table.querySelectorAll('th[aria-sort]').forEach(function (h) { h.setAttribute('aria-sort', 'none'); });
                th.setAttribute('aria-sort', next);

                var rows = originalOrder.slice();
                if (next !== 'none') {
                    rows.sort(function (a, b) {
                        var av = parseFloat(a.getAttribute('data-' + key));
                        var bv = parseFloat(b.getAttribute('data-' + key));
                        // schools without a figure always sink to the bottom
                        if (isNaN(av)) return 1;
                        if (isNaN(bv)) return -1;
                        return next === 'ascending' ? av - bv : bv - av;
                    });
                }
                rows.forEach(function (row) { tbody.appendChild(row); });
            });
        });
    }
})();
