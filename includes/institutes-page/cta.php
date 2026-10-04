<?php
/**
 * Closing call to action: how to join a school.
 */

declare(strict_types=1);
?>
<section class="icta" aria-labelledby="icta-title" data-parallax>
    <div class="icta__card reveal">
        <span class="icta__ring" aria-hidden="true"></span>
        <div class="icta__copy">
            <p class="section-eyebrow">Admissions</p>
            <h2 class="icta__title" id="icta-title">Ready to join an Anglican school?</h2>
            <ol class="icta__steps" role="list">
                <li><span>01</span> Apply through the ministry portal</li>
                <li><span>02</span> Review and, at some schools, an interview</li>
                <li><span>03</span> Acceptance and enrolment</li>
            </ol>
        </div>
        <div class="icta__actions">
            <a class="icta__button" href="about.php#admissions" data-magnetic>
                See the full process
                <span><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </a>
            <a class="icta__phone" href="tel:<?= e(SITE_PHONE) ?>">Call the Education Office · <?= e(SITE_PHONE_DISPLAY) ?></a>
        </div>
    </div>
</section>
