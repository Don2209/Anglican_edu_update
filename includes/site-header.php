<?php
/**
 * Header for inner pages. Uses the exact markup and classes of the homepage
 * hero's navigation (.hero__bar, .hero__menu …) so both pages share one
 * design; .site-header only pins it to the viewport and switches it to dark
 * text for white pages (see main.css). Behaviour: assets/js/header.js.
 */

declare(strict_types=1);

$currentPage = current_page();
// Same links as the homepage bar; the menu button opens the full list
$barLinks = array_intersect_key(NAV_LINKS, array_flip(['Home', 'About', 'Institutes', 'Academics', 'Contact']));
?>
<a class="skip-link" href="#main">Skip to content</a>
<div class="site-header" data-site-header>
    <header class="hero__bar">
        <div class="hero__brand-group">
            <button class="hero__menu-btn" type="button" aria-expanded="false" aria-controls="site-menu" data-header-toggle>
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
                    <li><a href="<?= e($href) ?>"<?= $href === $currentPage ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>

    <div class="hero__menu" id="site-menu" hidden data-header-menu>
        <nav aria-label="Full site">
            <ul>
                <?php foreach (NAV_LINKS as $label => $href): ?>
                    <li><a href="<?= e($href) ?>"<?= $href === $currentPage ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <a class="hero__menu-phone" href="tel:<?= e(SITE_PHONE) ?>">Call <?= e(SITE_PHONE_DISPLAY) ?></a>
    </div>
</div>
