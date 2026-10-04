/**
 * About page: chapter tabs (with the saltire wipe), shield navigator,
 * schools filter, admissions journey, subject explorer, curved photo reel
 * and the auto-playing projects showcase.
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
                // clear the fixed header and the sticky chapter tabs
                var tabsBar = bar.getBoundingClientRect();
                var offset = Math.max(150, tabsBar.height + 120);
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
        var lane = processEl.querySelector('[data-process-track]');
        var fill = processEl.querySelector('[data-process-fill]');
        var processSteps = Array.prototype.slice.call(processEl.querySelectorAll('[data-process-step]'));
        var readout = processEl.querySelector('[data-process-readout]');
        var readoutNum = processEl.querySelector('[data-process-current]');
        var wide = window.matchMedia('(min-width: 961px)');
        var activeStep = -1;

        readout.hidden = false;

        function setActiveStep(index) {
            if (index === activeStep) return;
            activeStep = index;
            processSteps.forEach(function (step, i) {
                step.classList.toggle('is-active', i === index);
                step.classList.toggle('is-passed', i < index);
            });
            readoutNum.textContent = (index < 9 ? '0' : '') + (index + 1);
        }

        var updateProcess = onFrame(function () {
            if (processEl.offsetParent === null) return;   // chapter hidden
            var rect = processEl.getBoundingClientRect();
            var vh = window.innerHeight;
            var p;

            if (wide.matches) {
                // Pinned: vertical scroll drives the lane sideways
                p = clamp01(-rect.top / Math.max(rect.height - vh, 1));
                var travel = Math.max(lane.scrollWidth - window.innerWidth, 0);
                lane.style.transform = reduceMotion ? '' : 'translate3d(' + (-travel * p).toFixed(1) + 'px, 0, 0)';
                setActiveStep(Math.min(processSteps.length - 1, Math.round(p * (processSteps.length - 1))));
            } else {
                // Vertical timeline: the step crossing the middle of the screen is active
                lane.style.transform = '';
                var mid = vh * 0.55;
                var index = 0;
                processSteps.forEach(function (step, i) {
                    if (step.getBoundingClientRect().top < mid) index = i;
                });
                setActiveStep(index);
                var laneRect = lane.getBoundingClientRect();
                p = clamp01((mid - laneRect.top) / Math.max(laneRect.height, 1));
            }
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
       Sports: curved 3D photo reel
       position is a fractional card index; each card is placed on an arc by
       its distance from it. Drag/swipe with momentum, then snap.
       ===================================================================== */

    var reel = document.querySelector('[data-reel]');
    if (reel) {
        var stage = reel.querySelector('[data-reel-stage]');
        var cards = Array.prototype.slice.call(reel.querySelectorAll('[data-reel-card]'));
        var prevBtn = reel.querySelector('[data-reel-prev]');
        var nextBtn = reel.querySelector('[data-reel-next]');
        var currentEl = reel.querySelector('[data-reel-current]');
        var progressEl = reel.querySelector('[data-reel-progress]');
        var reelStatus = reel.querySelector('[data-reel-status]');
        var pauseBtn = reel.querySelector('[data-reel-pause]');
        var last = cards.length - 1;
        var position = 0;      // where the reel is drawn
        var target = 0;        // where it is heading
        var settled = 0;       // last snapped card (for announcements)
        var raf = null;

        reel.querySelector('[data-reel-controls]').hidden = false;
        reel.querySelector('[data-reel-footer]').hidden = false;

        function spacing() { return cards[0].offsetWidth * (window.innerWidth < 640 ? 0.82 : 0.72); }

        function render() {
            var gap = spacing();
            cards.forEach(function (card, i) {
                var d = i - position;
                var ad = Math.abs(d);
                var t = reduceMotion
                    ? 'translate3d(' + (d * gap * 1.15).toFixed(1) + 'px,0,0)'
                    : 'translate3d(' + (d * gap).toFixed(1) + 'px,' + (ad * ad * 6).toFixed(1) + 'px,' + (-ad * 160).toFixed(1) + 'px) rotateY(' + (-d * 24).toFixed(2) + 'deg)';
                card.style.transform = t;
                card.style.zIndex = String(100 - Math.round(ad * 10));
                card.style.opacity = String(Math.max(0, 1 - Math.max(ad - 2.2, 0)));
                card.style.setProperty('--dist', ad.toFixed(3));
                card.classList.toggle('is-current', ad < 0.5);
                card.setAttribute('aria-hidden', ad < 0.5 ? 'false' : 'true');
            });
            var index = Math.round(clampIndex(position));
            var label = (index < 9 ? '0' : '') + (index + 1);
            if (currentEl.textContent !== label) currentEl.textContent = label;
            progressEl.style.setProperty('--progress', (last ? clamp01(position / last) : 1).toFixed(3));
            prevBtn.disabled = target <= 0;
            nextBtn.disabled = target >= last;
        }

        function clampIndex(n) { return Math.min(Math.max(n, 0), last); }

        // Ease position towards target each frame (critically damped feel)
        function animate() {
            var diff = target - position;
            position += diff * (reduceMotion ? 1 : 0.14);
            if (Math.abs(diff) < 0.001) position = target;
            render();
            if (position !== target) {
                raf = window.requestAnimationFrame(animate);
            } else {
                raf = null;
                if (Math.round(target) !== settled) {
                    settled = Math.round(target);
                    reelStatus.textContent = cards[settled].getAttribute('aria-label');
                }
            }
        }

        function goTo(index) {
            target = clampIndex(Math.round(index));
            if (!raf) raf = window.requestAnimationFrame(animate);
        }

        prevBtn.addEventListener('click', function () { goTo(target - 1); });
        nextBtn.addEventListener('click', function () { goTo(target + 1); });

        stage.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowRight') { goTo(target + 1); event.preventDefault(); }
            if (event.key === 'ArrowLeft') { goTo(target - 1); event.preventDefault(); }
            if (event.key === 'Home') { goTo(0); event.preventDefault(); }
            if (event.key === 'End') { goTo(last); event.preventDefault(); }
        });

        // Drag / swipe with momentum
        var drag = null;
        stage.addEventListener('pointerdown', function (event) {
            if (event.button !== 0) return;
            drag = { x: event.clientX, y: event.clientY, start: position, lastX: event.clientX, lastT: performance.now(), v: 0, moved: false, id: event.pointerId };
        });
        stage.addEventListener('pointermove', function (event) {
            if (!drag) return;
            var dx = event.clientX - drag.x;
            if (!drag.moved) {
                // decide: sideways drag (ours) or vertical scroll (the browser's)
                if (Math.abs(dx) < 6) return;
                if (Math.abs(event.clientY - drag.y) > Math.abs(dx)) { drag = null; return; }
                drag.moved = true;
                reel.classList.add('is-dragging');
                restartTimer();
                stage.setPointerCapture(drag.id);
            }
            var now = performance.now();
            drag.v = (event.clientX - drag.lastX) / Math.max(now - drag.lastT, 1);
            drag.lastX = event.clientX;
            drag.lastT = now;
            var raw = drag.start - dx / spacing();
            // rubber-band past the ends
            if (raw < 0) raw = raw * 0.35;
            if (raw > last) raw = last + (raw - last) * 0.35;
            position = target = raw;
            render();
        });
        function endDrag() {
            if (!drag) return;
            if (drag.moved) {
                var fling = -drag.v * 220 / spacing();   // px/ms → cards
                goTo(position + Math.max(Math.min(fling, 2.5), -2.5));
                // swallow the click that ends a drag
                stage.addEventListener('click', function swallow(e) { e.stopPropagation(); e.preventDefault(); }, { capture: true, once: true });
            }
            drag = null;
            reel.classList.remove('is-dragging');
        }
        stage.addEventListener('pointerup', endDrag);
        stage.addEventListener('pointercancel', endDrag);

        // Clicking a side card brings it to the centre
        cards.forEach(function (card, i) {
            card.addEventListener('click', function () { if (i !== Math.round(target)) goTo(i); });
        });

        // Trackpad sideways scroll
        var wheelLock = 0;
        stage.addEventListener('wheel', function (event) {
            if (Math.abs(event.deltaX) <= Math.abs(event.deltaY)) return;
            event.preventDefault();
            var now = performance.now();
            if (now - wheelLock < 350 || Math.abs(event.deltaX) < 8) return;
            wheelLock = now;
            goTo(target + (event.deltaX > 0 ? 1 : -1));
        }, { passive: false });

        // Re-measure whenever the reel's size changes, including when its
        // chapter goes from hidden (zero width) to shown
        if ('ResizeObserver' in window) new ResizeObserver(onFrame(render)).observe(stage);
        else window.addEventListener('resize', onFrame(render));
        window.addEventListener('chapter:change', render);

        /* --- Autoplay ------------------------------------------------------
           Advances every INTERVAL ms and loops. Pauses while hovered, focused,
           dragged, off screen, in a hidden chapter or background tab, and
           never runs for reduced motion. The play button shows the countdown. */
        var INTERVAL = 3500;
        var userPaused = reduceMotion;
        var hovering = false;
        var focused = false;
        var onScreen = false;
        var elapsed = 0;
        var lastTick = 0;
        var tickRaf = null;
        var lastTickValue = '';

        function canRun() {
            return !userPaused && !hovering && !focused && !drag && onScreen && !document.hidden && reel.offsetParent !== null;
        }

        function tick(now) {
            if (canRun()) {
                elapsed += lastTick ? now - lastTick : 0;
                if (elapsed >= INTERVAL) {
                    elapsed = 0;
                    goTo(Math.round(target) >= last ? 0 : target + 1);   // loop back to the start
                }
            }
            lastTick = now;
            var tickValue = (elapsed / INTERVAL).toFixed(3);
            if (tickValue !== lastTickValue) {
                lastTickValue = tickValue;
                pauseBtn.style.setProperty('--tick', tickValue);
            }
            tickRaf = window.requestAnimationFrame(tick);
        }

        function restartTimer() { elapsed = 0; }

        function setUserPaused(paused) {
            userPaused = paused;
            pauseBtn.setAttribute('aria-pressed', String(paused));
            pauseBtn.querySelector('.visually-hidden').textContent = paused ? 'Play slideshow' : 'Pause slideshow';
            restartTimer();
        }

        pauseBtn.addEventListener('click', function () { setUserPaused(!userPaused); });
        prevBtn.addEventListener('click', restartTimer);
        nextBtn.addEventListener('click', restartTimer);
        stage.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') hovering = true; });
        stage.addEventListener('pointerleave', function () { hovering = false; });
        stage.addEventListener('focus', function () { focused = true; });
        stage.addEventListener('blur', function () { focused = false; });
        stage.addEventListener('touchstart', restartTimer, { passive: true });

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { onScreen = entries[0].isIntersecting; }, { threshold: 0.5 }).observe(stage);
        } else {
            onScreen = true;
        }

        setUserPaused(userPaused);
        tickRaf = window.requestAnimationFrame(tick);
        // Start on the second card so the arc is visible on both sides
        position = target = settled = Math.min(1, last);
        render();
    }

    /* =====================================================================
       Projects: auto-playing showcase
       ===================================================================== */

    var showcase = document.querySelector('[data-showcase]');
    if (showcase) {
        var scTabs = Array.prototype.slice.call(showcase.querySelectorAll('[data-showcase-tab]'));
        var scSlides = Array.prototype.slice.call(showcase.querySelectorAll('[data-showcase-slide]'));
        var scPause = showcase.querySelector('[data-showcase-pause]');
        var scInterval = parseInt(showcase.getAttribute('data-interval'), 10) || 5000;
        var scIndex = 0;
        var scElapsed = 0;
        var scLast = 0;
        var scUserPaused = reduceMotion;
        var scHover = false;
        var scFocus = false;
        var scOnScreen = false;
        var scTickValue = '';

        showcase.querySelector('[data-showcase-tabs]').hidden = false;
        showcase.querySelector('[data-showcase-controls]').hidden = false;

        function scGo(index, focusTab) {
            scIndex = (index + scSlides.length) % scSlides.length;
            scElapsed = 0;
            scSlides.forEach(function (slide, i) {
                var active = i === scIndex;
                slide.classList.toggle('is-active', active);
                slide.toggleAttribute('inert', !active);
            });
            scTabs.forEach(function (tab, i) {
                var active = i === scIndex;
                tab.setAttribute('aria-selected', String(active));
                tab.tabIndex = active ? 0 : -1;
            });
            var tab = scTabs[scIndex];
            if (focusTab) tab.focus();
            // keep the chip in view when the list scrolls sideways (phones)
            var list = tab.parentElement;
            if (list.scrollWidth > list.clientWidth) {
                list.scrollTo({ left: tab.offsetLeft - (list.clientWidth - tab.offsetWidth) / 2, behavior: reduceMotion ? 'auto' : 'smooth' });
            }
        }

        function scRunning() {
            return !scUserPaused && !scHover && !scFocus && scOnScreen && !document.hidden && showcase.offsetParent !== null;
        }

        function scTick(now) {
            if (scRunning()) {
                scElapsed += scLast ? now - scLast : 0;
                if (scElapsed >= scInterval) scGo(scIndex + 1);
            }
            scLast = now;
            var value = Math.min(scElapsed / scInterval, 1).toFixed(3);
            if (value !== scTickValue) {
                scTickValue = value;
                scTabs[scIndex].style.setProperty('--tick', value);
            }
            window.requestAnimationFrame(scTick);
        }

        function scSetPaused(paused) {
            scUserPaused = paused;
            scPause.setAttribute('aria-pressed', String(paused));
            scPause.querySelector('.visually-hidden').textContent = paused ? 'Play slideshow' : 'Pause slideshow';
        }

        scTabs.forEach(function (tab, i) {
            tab.addEventListener('click', function () { scGo(i); });
        });

        // ARIA tabs keyboard pattern
        showcase.querySelector('[data-showcase-tabs]').addEventListener('keydown', function (event) {
            var keysMap = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 };
            if (event.key in keysMap) { event.preventDefault(); scGo(scIndex + keysMap[event.key], true); }
            if (event.key === 'Home') { event.preventDefault(); scGo(0, true); }
            if (event.key === 'End') { event.preventDefault(); scGo(scSlides.length - 1, true); }
        });

        showcase.querySelector('[data-showcase-prev]').addEventListener('click', function () { scGo(scIndex - 1); });
        showcase.querySelector('[data-showcase-next]').addEventListener('click', function () { scGo(scIndex + 1); });
        scPause.addEventListener('click', function () { scSetPaused(!scUserPaused); });

        // Pause while someone is looking closely or interacting
        showcase.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') scHover = true; });
        showcase.addEventListener('pointerleave', function () { scHover = false; });
        showcase.addEventListener('focusin', function () { scFocus = true; });
        showcase.addEventListener('focusout', function (e) { if (!showcase.contains(e.relatedTarget)) scFocus = false; });

        // Touch pauses briefly; a sideways swipe on the photo changes project
        var scTouch = null;
        var scTouchTimer;
        showcase.addEventListener('touchstart', function (e) {
            scTouch = { x: e.touches[0].clientX, y: e.touches[0].clientY };
            window.clearTimeout(scTouchTimer);
            scHover = true;
        }, { passive: true });
        showcase.addEventListener('touchend', function (e) {
            if (scTouch && e.target.closest('.showcase__media')) {
                var dx = e.changedTouches[0].clientX - scTouch.x;
                var dy = e.changedTouches[0].clientY - scTouch.y;
                if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) scGo(scIndex + (dx < 0 ? 1 : -1));
            }
            scTouch = null;
            scTouchTimer = window.setTimeout(function () { scHover = false; }, 4000);
        }, { passive: true });

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { scOnScreen = entries[0].isIntersecting; }, { threshold: 0.4 }).observe(showcase);
        } else {
            scOnScreen = true;
        }

        scSetPaused(scUserPaused);
        scGo(0);
        window.requestAnimationFrame(scTick);
    }
})();
