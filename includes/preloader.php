<?php
/**
 * Intro screen: the diocesan crest draws itself, then the overlay fades to
 * reveal the page. Styles are inlined in <head> (assets/css/preloader.css) so
 * it paints before anything else. Shown once per browser session.
 */

declare(strict_types=1);
?>
<div class="preloader" aria-hidden="true" data-preloader>
    <div class="preloader__inner">
        <div class="preloader__mark">
            <svg class="preloader__orbit" viewBox="0 0 240 240">
                <circle cx="120" cy="120" r="112" pathLength="1"/>
            </svg>

            <?php
            // Inline the real crest so it paints instantly and each part can be animated.
            $crest = require __DIR__ . '/partials/crest-svg.php';
            echo str_replace('<svg ', '<svg class="preloader__crest" focusable="false" ', $crest);
            ?>
        </div>

        <p class="preloader__eyebrow">Anglican Diocese of Harare</p>
        <p class="preloader__title">Education</p>
        <span class="preloader__bar"></span>
    </div>
</div>
<script>
    (function () {
        var el = document.querySelector('[data-preloader]');
        if (!el || document.documentElement.classList.contains('intro-seen')) {
            if (el) el.remove();
            return;
        }

        try { sessionStorage.setItem('ade-intro', '1'); } catch (e) {}

        // Let the drawing finish, but never hold the page longer than needed.
        var minTime = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 300 : 1900;
        var start = Date.now();

        function finish() {
            window.setTimeout(function () {
                el.classList.add('is-done');
                // Take it out of the DOM once faded (timer covers a missed transitionend)
                window.setTimeout(function () { el.remove(); }, 700);
            }, Math.max(0, minTime - (Date.now() - start)));
        }

        if (document.readyState === 'complete') finish();
        else window.addEventListener('load', finish, { once: true });
    })();
</script>
