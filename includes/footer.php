<?php
/**
 * Site footer, revealed like a curtain: it sits pinned beneath the page and
 * the last section slides up off it (only when it fits the screen; see
 * assets/js/sections.js). Content settles and the giant wordmark rises letter
 * by letter as it is revealed. Includes a floating back-to-top button whose
 * ring shows page progress.
 */

declare(strict_types=1);

$footerLearning = [
    'Primary Level'  => 'about.php#primary',
    'Ordinary Level' => 'about.php#ordinary',
    'Advanced Level' => 'about.php#advanced',
];

$footerInstitutes = [
    'Langham Girls High School' => 'about.php#langham',
    "St Oswald's"               => 'about.php#st-oswalds',
    "St John's Chikwaka"        => 'about.php#st-johns-chikwaka',
];

$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(implode(', ', SITE_ADDRESS));

/** Link whose text rolls up to a red copy on hover. */
$rollLink = static function (string $label, string $href, bool $external = false): string {
    $attrs = $external ? ' target="_blank" rel="noopener"' : '';
    $note  = $external ? '<span class="visually-hidden"> (opens in a new tab)</span>' : '';

    return '<a class="roll" href="' . e($href) . '"' . $attrs . '><span class="roll__text" data-text="' . e($label) . '">'
        . e($label) . '</span>' . $note . '</a>';
};

$wordmark = 'ANGLICAN EDUCATION';
?>
<footer class="footer" data-footer>
    <div class="footer__inner">

        <div class="footer__cta">
            <p class="footer__motto">Educating the nation <em>is a calling.</em></p>
            <div class="footer__cta-actions">
                <a class="footer__button" href="contact.php" data-magnetic>
                    Get in touch
                    <span class="footer__button-arrow"><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </a>
                <a class="footer__phone" href="tel:<?= e(SITE_PHONE) ?>"><?= e(SITE_PHONE_DISPLAY) ?></a>
            </div>
        </div>

        <div class="footer__grid">
            <div class="footer__about">
                <a class="footer__brand" href="index.php">
                    <img src="<?= asset('assets/images/brand/diocese-crest.webp') ?>" alt="" width="85" height="44" loading="lazy">
                    <span><?= e(SITE_NAME) ?></span>
                </a>
                <h2 class="footer__title">About Education</h2>
                <p class="footer__text">
                    The Education Department operates as a central governing body, ensuring effective management,
                    coordination, and support for all Anglican schools within the Harare Diocese.
                </p>
            </div>

            <nav class="footer__col" aria-labelledby="footer-learning">
                <h2 class="footer__title" id="footer-learning">Learning</h2>
                <ul class="footer__list" role="list">
                    <?php foreach ($footerLearning as $label => $href): ?>
                        <li><?= $rollLink($label, $href) ?></li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <nav class="footer__col" aria-labelledby="footer-institutes">
                <h2 class="footer__title" id="footer-institutes">Some Institutes</h2>
                <ul class="footer__list" role="list">
                    <?php foreach ($footerInstitutes as $label => $href): ?>
                        <li><?= $rollLink($label, $href) ?></li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="footer__col">
                <h2 class="footer__title">Social Media</h2>
                <ul class="footer__list footer__social" role="list">
                    <?php foreach (SOCIAL_LINKS as $key => $link): ?>
                        <li>
                            <svg class="footer__social-icon" aria-hidden="true" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><?= SOCIAL_ICONS[$key] ?? '' ?></svg>
                            <?php if ($link['url'] !== ''): ?>
                                <?= $rollLink($link['label'], $link['url'], true) ?>
                            <?php else: ?>
                                <span class="footer__muted"><?= e($link['label']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer__col">
                <h2 class="footer__title">Address</h2>
                <address class="footer__address">
                    <a class="footer__map" href="<?= e($mapUrl) ?>" target="_blank" rel="noopener">
                        <?= implode('<br>', array_map('e', SITE_ADDRESS)) ?>
                        <span class="visually-hidden"> (opens map in a new tab)</span>
                    </a>
                    <?= $rollLink(SITE_PHONE_DISPLAY, 'tel:' . SITE_PHONE) ?>
                </address>
            </div>
        </div>

        <p class="footer__wordmark" aria-hidden="true">
            <?php foreach (preg_split('//u', $wordmark, -1, PREG_SPLIT_NO_EMPTY) as $c => $char): ?><span style="--i: <?= $c ?>"><?= $char === ' ' ? '&nbsp;' : e($char) ?></span><?php endforeach; ?>
        </p>

        <div class="footer__bottom">
            <p>&copy; <?= date('Y') ?> <?= e(SITE_CREDIT_OWNER) ?>. All Rights Reserved.</p>
            <p>Designed by <a href="<?= e(SITE_CREDIT_URL) ?>" target="_blank" rel="noopener">VISYNTECH<span class="visually-hidden"> (opens in a new tab)</span></a></p>
        </div>
    </div>
</footer>

<a class="to-top" href="#main" data-to-top aria-label="Back to top">
    <svg class="to-top__ring" viewBox="0 0 48 48" aria-hidden="true">
        <circle cx="24" cy="24" r="22" pathLength="1"/>
        <circle class="to-top__progress" cx="24" cy="24" r="22" pathLength="1"/>
    </svg>
    <svg class="to-top__arrow" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
</a>
