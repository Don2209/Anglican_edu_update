/**
 * Homepage hero: slider, menu, and the scroll hand-off to the next section.
 * Progressive enhancement: the markup already shows slide 1 and a working
 * nav without this script.
 */
(function () {
    'use strict';

    var hero = document.querySelector('[data-hero]');
    if (!hero) return;

    initMenu(hero);
    initSlider(hero);
    initExit(hero);

    /**
     * As the next section slides up over the pinned hero, feed the scroll
     * progress (0-1) to CSS as --hero-exit, and mark the hero as covered once
     * it is fully hidden so the slideshow can stop.
     */
    function initExit(root) {
        var covered = false;
        var ticking = false;

        function update() {
            ticking = false;
            var height = root.offsetHeight || 1;
            var progress = Math.min(Math.max(window.scrollY / height, 0), 1);

            // On <html> so both the hero and the section covering it can react.
            // (A scroll-linked fade, not motion, so it applies with reduced motion too.)
            document.documentElement.style.setProperty('--hero-exit', progress.toFixed(3));

            var nowCovered = progress >= 1;
            if (nowCovered !== covered) {
                covered = nowCovered;
                root.classList.toggle('is-covered', covered);
                root.dispatchEvent(new Event('hero:visibility'));
            }
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }, { passive: true });
        window.addEventListener('resize', update);
        update();
    }

    function initSlider(root) {
        var slides = root.querySelectorAll('[data-hero-slide]');
        var thumbs = root.querySelectorAll('[data-hero-thumb]');
        var counts = root.querySelectorAll('[data-hero-count]');
        var status = root.querySelector('[data-hero-status]');
        var pauseBtn = root.querySelector('[data-hero-pause]');
        var interval = parseInt(root.getAttribute('data-interval'), 10) || 7000;
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

        if (slides.length < 2) return;

        var current = 0;
        var timer = null;
        var userPaused = reduceMotion.matches; // never auto-advance for reduced motion
        var hoverPaused = false;

        function eagerLoad(index) {
            var img = slides[index].querySelector('img');
            if (img && img.loading === 'lazy') img.loading = 'eager';
        }

        function goTo(index, announce) {
            index = (index + slides.length) % slides.length;
            if (index === current) return;

            eagerLoad(index);
            toggle(slides, index, function (el, active) {
                el.toggleAttribute('inert', !active);
                if (active) el.removeAttribute('aria-hidden');
                else el.setAttribute('aria-hidden', 'true');
            });
            toggle(thumbs, index, function (el, active) {
                if (active) el.setAttribute('aria-current', 'true');
                else el.removeAttribute('aria-current');
            });
            toggle(counts, index);

            current = index;
            eagerLoad((index + 1) % slides.length); // warm the next one

            if (announce && status) {
                status.textContent = slides[index].getAttribute('aria-label');
            }
            restart();
        }

        function toggle(list, index, extra) {
            for (var i = 0; i < list.length; i++) {
                var active = i === index;
                list[i].classList.toggle('is-active', active);
                if (extra) extra(list[i], active);
            }
        }

        function restart() {
            window.clearTimeout(timer);
            if (userPaused || hoverPaused || document.hidden || root.classList.contains('is-covered')) return;
            timer = window.setTimeout(function () { goTo(current + 1, false); }, interval);
        }

        function setUserPaused(paused) {
            userPaused = paused;
            pauseBtn.setAttribute('aria-pressed', String(paused));
            pauseBtn.querySelector('.visually-hidden').textContent = paused ? 'Play slideshow' : 'Pause slideshow';
            restart();
        }

        // Thumbnails
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                goTo(parseInt(thumb.getAttribute('data-hero-thumb'), 10), true);
            });
        });

        // Arrow keys while focus is anywhere in the carousel
        root.addEventListener('keydown', function (event) {
            if (event.target.closest('[data-menu]')) return;
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                goTo(current + 1, true);
                event.preventDefault();
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                goTo(current - 1, true);
                event.preventDefault();
            }
        });

        // Swipe on touch screens
        var startX = null;
        var card = root.querySelector('.hero__card');
        card.addEventListener('touchstart', function (event) {
            startX = event.touches[0].clientX;
        }, { passive: true });
        card.addEventListener('touchend', function (event) {
            if (startX === null) return;
            var dx = event.changedTouches[0].clientX - startX;
            startX = null;
            if (Math.abs(dx) > 50) goTo(current + (dx < 0 ? 1 : -1), true);
        }, { passive: true });

        // Pause while the visitor is reading or interacting (WCAG 2.2.2)
        card.addEventListener('mouseenter', function () { hoverPaused = true; restart(); });
        card.addEventListener('mouseleave', function () { hoverPaused = false; restart(); });
        root.addEventListener('focusin', function () { hoverPaused = true; restart(); });
        root.addEventListener('focusout', function (event) {
            if (!root.contains(event.relatedTarget)) { hoverPaused = false; restart(); }
        });
        document.addEventListener('visibilitychange', restart);
        root.addEventListener('hero:visibility', restart);

        pauseBtn.hidden = false;
        pauseBtn.addEventListener('click', function () { setUserPaused(!userPaused); });

        eagerLoad(1);
        setUserPaused(userPaused);
    }

    function initMenu(root) {
        var button = root.querySelector('[data-menu-toggle]');
        var menu = root.querySelector('[data-menu]');
        if (!button || !menu) return;

        var label = button.querySelector('.visually-hidden');

        function setOpen(open) {
            menu.hidden = !open;
            button.setAttribute('aria-expanded', String(open));
            label.textContent = open ? 'Close menu' : 'Open menu';
            if (open) {
                var first = menu.querySelector('a');
                if (first) first.focus();
            }
        }

        button.addEventListener('click', function () {
            setOpen(menu.hidden);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !menu.hidden) {
                setOpen(false);
                button.focus();
            }
        });

        document.addEventListener('click', function (event) {
            if (!menu.hidden && !menu.contains(event.target) && !button.contains(event.target)) {
                setOpen(false);
            }
        });
    }
})();
