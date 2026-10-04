<?php
/**
 * Office location. The Google map only loads when asked (faster page, and no
 * third-party requests until the visitor chooses); until then a designed
 * placeholder with a pulsing pin is shown.
 */

declare(strict_types=1);

$query = rawurlencode(SITE_ADDRESS[0] . ', ' . SITE_ADDRESS[1]);
$embedUrl = 'https://maps.google.com/maps?q=' . $query . '&z=16&output=embed';
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $query;
?>
<section class="visit" id="visit" aria-labelledby="visit-title">
    <div class="visit__inner">
        <header class="visit__header">
            <p class="section-eyebrow reveal">Visit us</p>
            <h2 class="visit__title split" id="visit-title">
                <?php foreach (['Find', 'the', 'Education', 'Office'] as $w => $word): ?><span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span> <?php endforeach; ?>
            </h2>
        </header>

        <div class="mapcard reveal" data-map data-embed="<?= e($embedUrl) ?>">
            <div class="mapcard__placeholder" data-map-placeholder>
                <div class="mapcard__streets" aria-hidden="true"></div>
                <div class="mapcard__pin" aria-hidden="true">
                    <span class="mapcard__pulse"></span><span class="mapcard__pulse"></span>
                    <svg viewBox="0 0 24 24" width="34" height="34" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                </div>
                <button class="mapcard__load" type="button" data-map-load hidden>
                    Load interactive map
                    <span class="mapcard__note">Loads content from Google</span>
                </button>
            </div>

            <div class="mapcard__address">
                <p class="mapcard__kicker">Anglican Diocese of Harare<br>Education Department</p>
                <address>
                    <?= e(SITE_ADDRESS[0]) ?><br>
                    <?= e(SITE_ADDRESS_DETAIL) ?><br>
                    <?= e(SITE_ADDRESS[1]) ?>
                </address>
                <a class="mapcard__dir" href="<?= e($mapsUrl) ?>" target="_blank" rel="noopener">
                    Open in Google Maps<span class="visually-hidden"> (opens in a new tab)</span>
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M7 17 17 7M9 7h8v8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>
