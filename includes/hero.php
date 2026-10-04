<?php
/**
 * Homepage hero: full-bleed image slider inside a rounded card, with
 * numbered progress, circular thumbnails and the primary navigation.
 * Behaviour lives in assets/js/hero.js; without JS the first slide shows.
 */

declare(strict_types=1);

$slides     = require __DIR__ . '/hero-slides.php';
$slideCount = count($slides);
$heroDir    = 'assets/images/hero/';
$page       = current_page();

// The bar shows the main sections; the menu button opens the full list.
$barLinks = array_intersect_key(NAV_LINKS, array_flip(['Home', 'About', 'Institutes', 'Academics', 'Contact']));

$srcset = static function (string $image) use ($heroDir): string {
    $sources = [];
    foreach ([768, 1280, 1920] as $width) {
        $sources[] = "{$heroDir}hero-{$image}-{$width}.webp {$width}w";
    }

    return e(implode(', ', $sources));
};

$socials = array_filter(SOCIAL_LINKS, static function (array $link): bool { return $link['url'] !== ''; });
?>
<section class="hero" id="hero" aria-roledescription="carousel" aria-label="Highlights from our schools" data-hero data-interval="5000">

    <div class="hero__card">

        <div class="hero__slides">
            <?php foreach ($slides as $i => $slide):
                $isFirst = $i === 0; ?>
                <div class="hero__slide<?= $isFirst ? ' is-active' : '' ?>"
                     id="hero-slide-<?= $i + 1 ?>"
                     role="group" aria-roledescription="slide"
                     aria-label="<?= $i + 1 ?> of <?= $slideCount ?>: <?= e($slide['title']) ?>"
                     <?= $isFirst ? '' : 'aria-hidden="true" inert' ?>
                     data-hero-slide>
                    <img class="hero__image"
                         src="<?= e($heroDir . 'hero-' . $slide['image'] . '-1280.webp') ?>"
                         srcset="<?= $srcset($slide['image']) ?>"
                         sizes="100vw"
                         width="1920" height="1440"
                         alt="<?= e($slide['alt']) ?>"
                         <?= $isFirst ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>

                    <div class="hero__caption">
                        <h2 class="hero__caption-title"><?= e($slide['title']) ?></h2>
                        <p class="hero__caption-text"><?= e($slide['text']) ?></p>
                        <a class="hero__cta" href="<?= e($slide['cta']['href']) ?>">
                            <?= e($slide['cta']['label']) ?>
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="hero__shade" aria-hidden="true"></div>
        <div class="hero__rings" aria-hidden="true"><span></span><span></span></div>

        <header class="hero__bar">
            <div class="hero__brand-group">
                <button class="hero__menu-btn" type="button" aria-expanded="false" aria-controls="site-menu" data-menu-toggle>
                    <span class="hero__menu-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Open menu</span>
                </button>
                <a class="hero__brand" href="index.php">
                    <img src="<?= asset('assets/images/brand/diocese-crest.webp') ?>" alt="" width="85" height="44">
                    <span class="visually-hidden"><?= e(SITE_NAME) ?> home</span>
                </a>
            </div>

            <nav class="hero__nav" aria-label="Main">
                <ul>
                    <?php foreach ($barLinks as $label => $href): ?>
                        <li><a href="<?= e($href) ?>"<?= $href === $page ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>

        <div class="hero__menu" id="site-menu" hidden data-menu>
            <nav aria-label="Full site">
                <ul>
                    <?php foreach (NAV_LINKS as $label => $href): ?>
                        <li><a href="<?= e($href) ?>"<?= $href === $page ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <a class="hero__menu-phone" href="tel:<?= e(SITE_PHONE) ?>">Call <?= e(SITE_PHONE_DISPLAY) ?></a>
        </div>

        <div class="hero__heading">
            <p class="hero__eyebrow">Anglican Diocese of Harare</p>
            <h1 class="hero__title">Education</h1>
        </div>

        <ol class="hero__counter" aria-hidden="true">
            <?php foreach ($slides as $i => $slide): ?>
                <li class="<?= $i === 0 ? 'is-active' : '' ?>" data-hero-count><?= sprintf('%02d', $i + 1) ?></li>
            <?php endforeach; ?>
        </ol>

        <div class="hero__thumbs" aria-label="Choose a slide" role="group">
            <?php foreach ($slides as $i => $slide): ?>
                <button class="hero__thumb<?= $i === 0 ? ' is-active' : '' ?>" type="button"
                        aria-controls="hero-slide-<?= $i + 1 ?>"
                        <?= $i === 0 ? 'aria-current="true"' : '' ?>
                        data-hero-thumb="<?= $i ?>">
                    <img src="<?= e($heroDir . 'hero-' . $slide['image'] . '-thumb.webp') ?>"
                         alt="" width="192" height="192" loading="lazy" decoding="async">
                    <span class="visually-hidden">Show slide <?= $i + 1 ?>: <?= e($slide['title']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <a class="hero__scroll" href="#about">
            <span class="hero__scroll-line" aria-hidden="true"></span>
            Scroll<span class="visually-hidden"> to About the Department</span>
        </a>

        <footer class="hero__footer">
            <button class="hero__play" type="button" aria-pressed="false" data-hero-pause hidden>
                <svg class="hero__play-pause" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                <svg class="hero__play-play" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M8 5v14l11-7z"/></svg>
                <span class="visually-hidden">Pause slideshow</span>
            </button>

            <?php if ($socials): ?>
                <ul class="hero__social">
                    <?php foreach ($socials as $key => $link): ?>
                        <li>
                            <a href="<?= e($link['url']) ?>" rel="noopener" target="_blank">
                                <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><?= SOCIAL_ICONS[$key] ?? '' ?></svg>
                                <span class="visually-hidden"><?= e($link['label']) ?> (opens in a new tab)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <a class="hero__phone" href="tel:<?= e(SITE_PHONE) ?>">
                <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>
                <?= e(SITE_PHONE_DISPLAY) ?>
            </a>
        </footer>

        <p class="visually-hidden" aria-live="polite" aria-atomic="true" data-hero-status></p>
    </div>
</section>
