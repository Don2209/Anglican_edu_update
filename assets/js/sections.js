/**
 * Below-the-fold sections: scroll-linked parallax, fade-up / word-by-word
 * reveals and number count-up. Content is fully visible and correct without
 * this script.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var revealEls = document.querySelectorAll('.reveal, .split, .stagger, .reveal-clip');
    var counters = document.querySelectorAll('[data-count-to]');

    // Only run the tunnel animation while it is on screen
    var tunnel = document.querySelector('[data-tunnel]');
    if (tunnel && 'IntersectionObserver' in window) {
        tunnel.classList.add('is-paused');
        new IntersectionObserver(function (entries) {
            tunnel.classList.toggle('is-paused', !entries[0].isIntersecting);
        }).observe(tunnel);
    }

    initStackFit();
    initFooter();
    if (!reduceMotion) initMagnetic();

    /**
     * Footer curtain reveal + back-to-top progress ring.
     * - The footer pins beneath the page (CSS sticky) only when it fits the
     *   screen, so nothing in a tall footer can end up hidden.
     * - --reveal (0 -> 1) tracks how much of the footer is uncovered.
     * - --page (0 -> 1) is overall scroll progress for the back-to-top ring.
     */
    function initFooter() {
        var footer = document.querySelector('[data-footer]');
        var main = document.querySelector('main');
        var toTop = document.querySelector('[data-to-top]');
        if (!footer || !main) return;
        var ticking = false;

        function fit() {
            footer.classList.toggle('is-curtain', footer.offsetHeight <= window.innerHeight);
            update();
        }

        function update() {
            ticking = false;
            var vh = window.innerHeight;
            var reveal = (vh - main.getBoundingClientRect().bottom) / footer.offsetHeight;
            if (!reduceMotion) footer.style.setProperty('--reveal', Math.min(Math.max(reveal, 0), 1).toFixed(4));

            if (toTop) {
                var max = document.documentElement.scrollHeight - vh;
                toTop.style.setProperty('--page', max > 0 ? (window.scrollY / max).toFixed(4) : '0');
                toTop.classList.toggle('is-shown', window.scrollY > vh * 0.8);
            }
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }, { passive: true });

        var timer;
        window.addEventListener('resize', function () {
            window.clearTimeout(timer);
            timer = window.setTimeout(fit, 150);
        });
        window.addEventListener('load', fit);
        fit();
    }

    /** Buttons that lean toward the cursor (fine pointers only). */
    function initMagnetic() {
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        document.querySelectorAll('[data-magnetic]').forEach(function (el) {
            el.addEventListener('pointermove', function (event) {
                var rect = el.getBoundingClientRect();
                var x = event.clientX - rect.left - rect.width / 2;
                var y = event.clientY - rect.top - rect.height / 2;
                el.style.transform = 'translate(' + (x * 0.25).toFixed(1) + 'px, ' + (y * 0.35).toFixed(1) + 'px)';
            });
            el.addEventListener('pointerleave', function () { el.style.transform = ''; });
        });
    }
    if ('IntersectionObserver' in window && !reduceMotion) {
        initParallax();
        initStack();
    }

    /**
     * Turn on card stacking only when every card fits on screen below its pin
     * point. On short screens (or with large text) a card can be taller than
     * that, and pinning would let the next card cover it before it is read.
     */
    function initStackFit() {
        var stack = document.querySelector('[data-stack]');
        if (!stack) return;
        var cards = stack.querySelectorAll('[data-stack-card]');

        function check() {
            var fits = true;
            cards.forEach(function (card) {
                // --stack-top is a calc(); applying it to `top` lets the browser resolve it to px
                card.style.top = 'var(--stack-top)';
                var top = parseFloat(getComputedStyle(card).top) || 0;
                card.style.top = '';
                if (card.offsetHeight + top + 16 > window.innerHeight) fits = false;
            });
            stack.classList.toggle('is-stacking', fits);
        }

        check();
        var timer;
        window.addEventListener('resize', function () {
            window.clearTimeout(timer);
            timer = window.setTimeout(check, 150);
        });
        // Card heights settle once web fonts and images arrive
        window.addEventListener('load', check);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(check);
    }
    initSpotlight();

    /**
     * Stacking cards: each card pins (CSS position: sticky). As the next card
     * slides over it, --cover goes 0 -> 1 on the card underneath so CSS can
     * shrink and dim it.
     */
    function initStack() {
        var stack = document.querySelector('[data-stack]');
        if (!stack) return;
        var cards = stack.querySelectorAll('[data-stack-card]');
        var active = false;
        var ticking = false;

        function update() {
            ticking = false;
            for (var i = 0; i < cards.length - 1; i++) {
                var current = cards[i].getBoundingClientRect();
                var next = cards[i + 1].getBoundingClientRect();
                var cover = 1 - (next.top - current.top) / current.height;
                cards[i].style.setProperty('--cover', Math.min(Math.max(cover, 0), 1).toFixed(4));
            }
        }

        function requestUpdate() {
            if (active && !ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }

        new IntersectionObserver(function (entries) {
            active = entries[0].isIntersecting;
            requestUpdate();
        }).observe(stack);
        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', requestUpdate);
    }

    /** Soft light that follows the cursor across [data-spotlight] cards. */
    function initSpotlight() {
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        document.querySelectorAll('[data-spotlight]').forEach(function (card) {
            card.addEventListener('pointermove', function (event) {
                var rect = card.getBoundingClientRect();
                card.style.setProperty('--mx', (event.clientX - rect.left) + 'px');
                card.style.setProperty('--my', (event.clientY - rect.top) + 'px');
            });
        });
    }

    /**
     * Scroll engine: every [data-parallax] element gets --p, its progress
     * through the viewport (0 = its top just entered at the bottom,
     * 1 = its bottom just left at the top). CSS turns --p into transforms.
     * Only on-screen elements are measured, once per animation frame.
     */
    function initParallax() {
        var els = document.querySelectorAll('[data-parallax]');
        var visible = new Set();
        var ticking = false;

        function update() {
            ticking = false;
            var vh = window.innerHeight;
            visible.forEach(function (el) {
                var rect = el.getBoundingClientRect();
                var p = (vh - rect.top) / (vh + rect.height);
                el.style.setProperty('--p', Math.min(Math.max(p, 0), 1).toFixed(4));
            });
        }

        function requestUpdate() {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) visible.add(entry.target);
                else visible.delete(entry.target);
            });
            requestUpdate();
        }, { rootMargin: '20% 0px' });

        els.forEach(function (el) { observer.observe(el); });
        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', requestUpdate);
    }

    if (!('IntersectionObserver' in window) || reduceMotion) {
        revealEls.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.15 });

    revealEls.forEach(function (el) { revealObserver.observe(el); });

    var counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            countUp(entry.target);
            counterObserver.unobserve(entry.target);
        });
    }, { threshold: 0.6 });

    counters.forEach(function (el) {
        el.textContent = '0';
        counterObserver.observe(el);
    });

    function countUp(el) {
        var target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
        var duration = 1600;
        var start = null;
        var format = new Intl.NumberFormat('en');

        function step(time) {
            if (start === null) start = time;
            var progress = Math.min((time - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
            el.textContent = format.format(Math.round(target * eased));
            if (progress < 1) window.requestAnimationFrame(step);
        }

        window.requestAnimationFrame(step);
    }
})();
