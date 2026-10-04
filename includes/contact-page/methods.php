<?php
/**
 * Ways to reach us: call, email, visit (plus social links once configured).
 */

declare(strict_types=1);

$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(SITE_ADDRESS[0] . ', ' . SITE_ADDRESS[1]);
$socials = array_filter(SOCIAL_LINKS, static fn ($l) => $l['url'] !== '');
?>
<section class="methods" aria-labelledby="methods-title">
    <h2 class="visually-hidden" id="methods-title">Ways to reach us</h2>
    <ul class="methods__grid" role="list">
        <li class="method method--call reveal" style="--delay: 0ms" data-spotlight>
            <span class="method__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg>
            </span>
            <h3 class="method__title">Call us</h3>
            <p class="method__value"><?= e(SITE_PHONE_DISPLAY) ?></p>
            <p class="method__note">Speak to the Education Office directly.</p>
            <div class="method__actions">
                <a class="method__btn" href="tel:<?= e(SITE_PHONE) ?>">Call now</a>
                <button class="method__copy" type="button" data-copy="<?= e(SITE_PHONE_DISPLAY) ?>" hidden>Copy number</button>
            </div>
        </li>

        <li class="method method--email reveal" style="--delay: 90ms" data-spotlight>
            <span class="method__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path class="method__flap" d="m3 7 9 6 9-6"/></svg>
            </span>
            <h3 class="method__title">Email us</h3>
            <p class="method__value method__value--email"><?= e(SITE_EMAIL) ?></p>
            <p class="method__note">We reply as soon as we can.</p>
            <div class="method__actions">
                <a class="method__btn" href="mailto:<?= e(SITE_EMAIL) ?>">Write an email</a>
                <button class="method__copy" type="button" data-copy="<?= e(SITE_EMAIL) ?>" hidden>Copy address</button>
            </div>
        </li>

        <li class="method method--visit reveal" style="--delay: 180ms" data-spotlight>
            <span class="method__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s7-7.5 7-13a7 7 0 0 0-14 0c0 5.5 7 13 7 13z"/><circle cx="12" cy="9" r="2.5"/></svg>
            </span>
            <h3 class="method__title">Visit us</h3>
            <p class="method__value"><?= e(SITE_ADDRESS[0]) ?></p>
            <p class="method__note"><?= e(SITE_ADDRESS_DETAIL) ?>, <?= e(SITE_ADDRESS[1]) ?></p>
            <div class="method__actions">
                <a class="method__btn" href="<?= e($mapsUrl) ?>" target="_blank" rel="noopener">Get directions<span class="visually-hidden"> (opens Google Maps in a new tab)</span></a>
            </div>
        </li>
    </ul>

    <?php if ($socials): ?>
        <ul class="methods__social reveal" role="list">
            <?php foreach ($socials as $key => $link): ?>
                <li><a href="<?= e($link['url']) ?>" target="_blank" rel="noopener">
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><?= SOCIAL_ICONS[$key] ?? '' ?></svg>
                    <?= e($link['label']) ?><span class="visually-hidden"> (opens in a new tab)</span>
                </a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p class="toast" role="status" aria-live="polite" data-toast></p>
</section>
