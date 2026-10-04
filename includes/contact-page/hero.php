<?php
/**
 * Contact hero: headline, and a dark "office card" with a live Harare clock
 * and a signal animation around the call button.
 *
 * @var DateTimeImmutable $now  current time in SITE_TIMEZONE
 */

declare(strict_types=1);
?>
<section class="chero" aria-labelledby="chero-title" data-parallax>
    <span class="chero__glow" aria-hidden="true"></span>
    <div class="chero__inner">
        <div class="chero__copy">
            <p class="section-eyebrow chero__fade" style="--d: 0">Contact Us</p>
            <h1 class="chero__title" id="chero-title">
                <span class="chero__line"><span style="--d: 1">Let's</span></span>
                <span class="chero__line"><span style="--d: 2"><em>talk.</em></span></span>
            </h1>
            <p class="chero__lead chero__fade" style="--d: 3">
                Questions about admissions, a particular school or working with the diocese? The Education
                Department team is here to help. Call, email, visit, or send a message below.
            </p>
            <div class="chero__actions chero__fade" style="--d: 4">
                <a class="button" href="#message">Send a message</a>
                <a class="chero__link" href="#visit">Find our office</a>
            </div>
        </div>

        <aside class="office chero__fade" style="--d: 3" aria-label="Education Office">
            <div class="office__signal" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
            <a class="office__call" href="tel:<?= e(SITE_PHONE) ?>">
                <svg aria-hidden="true" viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>
                <span class="visually-hidden">Call the Education Office</span>
            </a>

            <p class="office__kicker">Education Office</p>
            <p class="office__phone"><a href="tel:<?= e(SITE_PHONE) ?>"><?= e(SITE_PHONE_DISPLAY) ?></a></p>

            <dl class="office__meta">
                <div>
                    <dt>Local time in Harare</dt>
                    <dd><time class="office__clock" datetime="<?= $now->format(DATE_ATOM) ?>" data-clock><?= $now->format('H:i') ?></time>
                        <span class="office__day" data-clock-day><?= $now->format('l') ?></span></dd>
                </div>
                <div>
                    <dt>Find us</dt>
                    <dd><?= e(SITE_ADDRESS[0]) ?>, <?= e(SITE_ADDRESS_DETAIL) ?></dd>
                </div>
            </dl>
        </aside>
    </div>
</section>
