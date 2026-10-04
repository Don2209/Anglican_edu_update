<?php
/**
 * Kinetic band between "About" and "Discover More": two crossing tape bands
 * of large type with inline photo pills. They slide in opposite directions as
 * the page scrolls (scroll-linked via --p from assets/js/sections.js).
 * Purely decorative; the visually hidden sentence carries the meaning.
 */

declare(strict_types=1);

$pillDir = 'assets/images/about/tunnel/';

// Band A: the four pillars, each followed by a small photo
$bandA = [
    ['Faith', 'tunnel-bishop'],
    ['Excellence', 'tunnel-classroom-lesson'],
    ['Affordability', 'tunnel-senior-students'],
    ['Sport & Culture', 'tunnel-relay-race'],
];
// Band B: the motto, in outline type
$bandB = ['Educating the nation', 'is a calling'];

/** Repeat a band's content so it always overflows the screen while sliding. */
$repeat = 3;
?>
<section class="marquee" aria-label="Our values" data-parallax>
    <p class="visually-hidden">Faith, excellence, affordability, sport and culture. Educating the nation is a calling.</p>

    <div class="marquee__band marquee__band--a" aria-hidden="true">
        <div class="marquee__track">
            <?php for ($n = 0; $n < $repeat; $n++): ?>
                <?php foreach ($bandA as [$word, $image]): ?>
                    <span class="marquee__word"><?= e($word) ?></span>
                    <span class="marquee__pill">
                        <img src="<?= e($pillDir . $image . '.webp') ?>" alt="" width="480" height="360" loading="lazy" decoding="async">
                    </span>
                    <span class="marquee__star">✦</span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>

    <div class="marquee__band marquee__band--b" aria-hidden="true">
        <div class="marquee__track">
            <?php for ($n = 0; $n < $repeat * 2; $n++): ?>
                <?php foreach ($bandB as $phrase): ?>
                    <span class="marquee__word"><?= e($phrase) ?></span>
                    <span class="marquee__star">✦</span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
