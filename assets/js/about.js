/**
 * About page: chapter tabs (with the saltire wipe), shield navigator,
 * schools filter, horizontal admissions process, subject explorer, draggable
 * strip and the projects scroll story.
 * Everything is optional enhancement: without JS all chapters are shown.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    /** Run fn at most once per animation frame for scroll/resize. */
    function onFrame(fn) {
        var ticking = false;
        return function () {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(function () { ticking = false; fn(); });
        };
    }

    function clamp01(n) { return Math.min(Math.max(n, 0), 1); }

    /* =====================================================================
       Chapters (tabs)
       ===================================================================== */

    var root = document.querySelector('[data-chapters]');
    if (!root) return;

    var bar = root.querySelector('[data-chapters-bar]');
    var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-tab]'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('[data-panel]'));
    var indicator = root.querySelector('[data-tab-indicator]');
    var wipe = root.querySelector('[data-wipe]');
    var status = root.querySelector('[data-chapter-status]');
    var keys = tabs.map(function (t) { return t.getAttribute('data-tab'); });
    var current = null;
    var busy = false;

    bar.hidden = false;

    function panelFor(key) { return root.querySelector('[data-panel="' + key + '"]'); }
    function tabFor(key) { return root.querySelector('[data-tab="' + key + '"]'); }

    function moveIndicator() {
        var tab = tabFor(current);
        if (!tab) return;
        indicator.style.setProperty('--x', tab.offsetLeft + 'px');
        indicator.style.setProperty('--w', tab.offsetWidth + 'px');
        // keep the selected tab visible when the bar scrolls on small screens
        var strip = tab.parentElement;
        if (strip.scrollWidth > strip.clientWidth) {
            strip.scrollTo({ left: tab.offsetLeft - (strip.clientWidth - tab.offsetWidth) / 2, behavior: reduceMotion ? 'auto' : 'smooth' });
        }
    }

    /** Swap the visible chapter (no animation). */
    function show(key) {
        current = key;
        panels.forEach(function (panel) {
            var active = panel.getAttribute('data-panel') === key;
            panel.hidden = !active;
            panel.classList.toggle('is-entering', active);
        });
        tabs.forEach(function (tab) {
            var active = tab.getAttribute('data-tab') === key;
            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
        });
        moveIndicator();
        window.dispatchEvent(new Event('chapter:change'));
        updateProgress();
    }

    function scrollToChapters() {
        var top = root.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({ top: top, behavior: 'auto' });
    }

    /**
     * Open a chapter. With motion, the crest's red saltire bursts across the
     * screen and the chapter is swapped while it covers everything.
     */
    function open(key, options) {
        options = options || {};
        if (keys.indexOf(key) === -1 || busy) return;
        var target = options.target || null;

        function swap() {
            if (key !== current) show(key);
            if (target) {
                var details = target.querySelector('details');
                if (details) details.open = true;
                var offset = 150;
                window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'auto' });
            } else if (options.scroll !== false) {
                scrollToChapters();
            }
        }

        var hash = '#' + (target ? target.id : key);
        if (location.hash !== hash) history.replaceState(null, '', hash);
        status.textContent = 'Showing chapter: ' + tabFor(key).querySelector('.chapters__tab-label').textContent;

        if (!options.animate || reduceMotion || (key === current && !target)) {
            swap();
            if (options.focusTab) tabFor(key).focus();
            return;
        }

        busy = true;
        wipe.classList.remove('is-playing');
        void wipe.offsetWidth;
        wipe.classList.add('is-playing');
        window.setTimeout(function () {
            swap();
            if (options.focusTab) tabFor(key).focus({ preventScroll: true });
        }, 380);
        window.setTimeout(function () {
            wipe.classList.remove('is-playing');
            busy = false;
        }, 840);
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            open(tab.getAttribute('data-tab'), { animate: true });
        });
    });

    // ARIA tabs keyboard pattern: arrows, Home, End
    bar.addEventListener('keydown', function (event) {
        var i = keys.indexOf(current);
        var next = null;
        if (event.key === 'ArrowRight') next = keys[(i + 1) % keys.length];
        if (event.key === 'ArrowLeft') next = keys[(i - 1 + keys.length) % keys.length];
        if (event.key === 'Home') next = keys[0];
        if (event.key === 'End') next = keys[keys.length - 1];
        if (next) {
            event.preventDefault();
            open(next, { animate: false, scroll: false, focusTab: true });
        }
    });

    // Any link into a chapter (shield quarters, "next chapter")
    document.addEventListener('click', function (event) {
        var link = event.target.closest('[data-chapter-link]');
        if (!link) return;
        event.preventDefault();
        open(link.getAttribute('data-chapter-link'), { animate: true });
    });

    /** Resolve a URL hash to a chapter (and optional element inside it). */
    function fromHash(animate) {
        var id = decodeURIComponent(location.hash.slice(1));
        if (!id) return false;
        if (keys.indexOf(id) !== -1) {
            open(id, { animate: animate });
            return true;
        }
        var el = document.getElementById(id);
        var panel = el && el.closest('[data-panel]');
        if (panel) {
            open(panel.getAttribute('data-panel'), { animate: animate, target: el });
            return true;
        }
        return false;
    }

    window.addEventListener('hashchange', function () { fromHash(true); });
    window.addEventListener('resize', onFrame(moveIndicator));
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(moveIndicator);

    // Initial state: the chapter named in the URL, or the first one
    show(keys[0]);
    if (location.hash) {
        // wait a frame so layout (and the sticky bar) is in place before scrolling
        window.requestAnimationFrame(function () { fromHash(false); });
    }

    /* --- Reading progress (underline on the selected tab) ----------------- */

    function updateProgress() {
        var panel = panelFor(current);
        if (!panel) return;
        var rect = panel.getBoundingClientRect();
        var vh = window.innerHeight;
        var progress = clamp01((vh * 0.35 - rect.top) / Math.max(rect.height - vh * 0.5, 1));
        var tab = tabFor(current);
        tab.style.setProperty('--progress', progress.toFixed(3));
    }

    window.addEventListener('scroll', onFrame(updateProgress), { passive: true });
    window.addEventListener('resize', onFrame(updateProgress));

    /* =====================================================================
       Shield navigator: medallion + tilt
       ===================================================================== */

    var shield = document.querySelector('[data-shield]');
    if (shield) {
        var svg = shield.querySelector('svg');
        var medalNum = shield.querySelector('[data-shield-num]');
        var medalText = shield.querySelector('[data-shield-text]');

        shield.querySelectorAll('.shield__q').forEach(function (q) {
            var label = q.getAttribute('aria-label').split(' ');
            function point() {
                medalNum.textContent = label[0];
                medalText.textContent = q.querySelector('.shield__label').textContent;
            }
            function reset() {
                medalNum.textContent = '✦';
                medalText.textContent = 'Explore';
            }
            q.addEventListener('pointerenter', point);
            q.addEventListener('focus', point);
            q.addEventListener('pointerleave', reset);
            q.addEventListener('blur', reset);
        });

        if (finePointer && !reduceMotion) {
            shield.addEventListener('pointermove', function (event) {
                var rect = shield.getBoundingClientRect();
                var x = (event.clientX - rect.left) / rect.width - 0.5;
                var y = (event.clientY - rect.top) / rect.height - 0.5;
                svg.style.setProperty('--ry', (x * 14).toFixed(2) + 'deg');
                svg.style.setProperty('--rx', (y * -14).toFixed(2) + 'deg');
            });
            shield.addEventListener('pointerleave', function () {
                svg.style.setProperty('--ry', '0deg');
                svg.style.setProperty('--rx', '0deg');
            });
        }
    }

    /* =====================================================================
       Institutions: filter
       ===================================================================== */

    var schoolFilter = document.querySelector('[data-school-filter]');
    if (schoolFilter) {
        schoolFilter.hidden = false;
        var schoolItems = document.querySelectorAll('[data-schools] > li');
        schoolFilter.addEventListener('click', function (event) {
            var button = event.target.closest('[data-level]');
            if (!button) return;
            var level = button.getAttribute('data-level');
            schoolFilter.querySelectorAll('[data-level]').forEach(function (b) {
                b.setAttribute('aria-pressed', String(b === button));
            });
            schoolItems.forEach(function (item) {
                item.hidden = level !== 'all' && item.getAttribute('data-level') !== level;
                item.classList.add('is-visible');
            });
        });
    }

    /* =====================================================================
       Admissions: vertical scroll drives the steps sideways
       ===================================================================== */

    var processEl = document.querySelector('[data-process]');
    if (processEl) {
        var track = processEl.querySelector('[data-process-track]');
        var fill = processEl.querySelector('[data-process-fill]');
        var wide = window.matchMedia('(min-width: 961px)');

        var updateProcess = onFrame(function () {
            if (!wide.matches || reduceMotion || processEl.offsetParent === null) {
                track.style.transform = '';
                return;
            }
            var rect = processEl.getBoundingClientRect();
            var distance = rect.height - window.innerHeight;
            var p = clamp01(-rect.top / Math.max(distance, 1));
            var travel = Math.max(track.scrollWidth - window.innerWidth, 0);
            track.style.transform = 'translate3d(' + (-travel * p).toFixed(1) + 'px, 0, 0)';
            fill.style.setProperty('--progress', p.toFixed(3));
        });

        window.addEventListener('scroll', updateProcess, { passive: true });
        window.addEventListener('resize', updateProcess);
        window.addEventListener('chapter:change', updateProcess);
        updateProcess();
    }

    /* =====================================================================
       Academics: subject explorer
       ===================================================================== */

    var explorer = document.querySelector('[data-explorer]');
    if (explorer) {
        var controls = explorer.querySelector('[data-explorer-controls]');
        var levelButtons = explorer.querySelectorAll('[data-explorer-level]');
        var groups = explorer.querySelectorAll('[data-explorer-group]');
        var search = explorer.querySelector('[data-explorer-search]');
        var empty = explorer.querySelector('[data-explorer-empty]');
        var explorerStatus = explorer.querySelector('[data-explorer-status]');
        var level = levelButtons[0].getAttribute('data-explorer-level');

        controls.hidden = false;

        function render() {
            var query = search.value.trim().toLowerCase();
            var searching = query !== '';
            var total = 0;
            explorer.classList.toggle('is-searching', searching);

            groups.forEach(function (group) {
                var key = group.getAttribute('data-explorer-group');
                var count = 0;
                group.querySelectorAll('[data-subject]').forEach(function (chip) {
                    var match = !searching || chip.getAttribute('data-subject').indexOf(query) !== -1;
                    chip.hidden = !match;
                    chip.classList.toggle('is-match', searching && match);
                    if (match) count++;
                });
                group.querySelector('[data-group-count]').textContent = count;
                var visible = searching ? count > 0 : key === level;
                var wasHidden = group.hidden;
                group.hidden = !visible;
                if (visible && wasHidden) {
                    group.classList.remove('is-shown');
                    void group.offsetWidth;
                    group.classList.add('is-shown');
                }
                if (visible) total += count;
            });

            empty.hidden = !(searching && total === 0);
            explorerStatus.textContent = searching
                ? total + ' subject' + (total === 1 ? '' : 's') + ' found'
                : total + ' subjects shown';
        }

        levelButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                level = button.getAttribute('data-explorer-level');
                levelButtons.forEach(function (b) { b.setAttribute('aria-pressed', String(b === button)); });
                search.value = '';
                render();
            });
        });
        search.addEventListener('input', render);

        // Deep links like about.php#ordinary pick that level
        function levelFromHash() {
            var id = location.hash.slice(1);
            explorer.querySelectorAll('[data-explorer-level="' + id + '"]').forEach(function (b) { b.click(); });
        }
        window.addEventListener('hashchange', levelFromHash);
        groups.forEach(function (g) { g.hidden = g.getAttribute('data-explorer-group') !== level; });
        render();
        levelFromHash();
    }

    /* =====================================================================
       Sports: drag-to-scroll photo strip (mouse; touch scrolls natively)
       ===================================================================== */

    var strip = document.querySelector('[data-strip]');
    if (strip && finePointer) {
        var stripTrack = strip.querySelector('.strip__track');
        var startX = 0;
        var startScroll = 0;
        var dragging = false;

        stripTrack.addEventListener('pointerdown', function (event) {
            if (event.pointerType !== 'mouse') return;
            dragging = true;
            startX = event.clientX;
            startScroll = stripTrack.scrollLeft;
            strip.classList.add('is-dragging');
            stripTrack.setPointerCapture(event.pointerId);
        });
        stripTrack.addEventListener('pointermove', function (event) {
            if (dragging) stripTrack.scrollLeft = startScroll - (event.clientX - startX);
        });
        ['pointerup', 'pointercancel'].forEach(function (type) {
            stripTrack.addEventListener(type, function () {
                dragging = false;
                strip.classList.remove('is-dragging');
            });
        });
    }

    /* =====================================================================
       Projects: scroll story
       ===================================================================== */

    var story = document.querySelector('[data-story]');
    if (story && 'IntersectionObserver' in window) {
        var steps = story.querySelectorAll('[data-story-step]');
        var layers = story.querySelectorAll('[data-story-layer]');
        var dots = story.querySelectorAll('[data-story-dot]');
        var counter = story.querySelector('[data-story-current]');

        function activate(index) {
            steps.forEach(function (s, i) { s.classList.toggle('is-active', i === index); });
            layers.forEach(function (l, i) { l.classList.toggle('is-active', i === index); });
            dots.forEach(function (d, i) { d.classList.toggle('is-active', i === index); });
            counter.textContent = (index < 9 ? '0' : '') + (index + 1);
        }

        var storyObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) activate(parseInt(entry.target.getAttribute('data-story-step'), 10));
            });
        }, { rootMargin: '-45% 0px -45% 0px' });

        steps.forEach(function (step) { storyObserver.observe(step); });
    }
})();
