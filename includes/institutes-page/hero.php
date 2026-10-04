<?php
/**
 * Institutions hero: headline, live stats (computed from the school data)
 * and a full-width expanding gallery: one panel per school; the active one
 * opens wide (autoplay, hover/tap, arrow keys; assets/js/institutes.js).
 *
 * @var array $schools
 * @var array $stats
 */

declare(strict_types=1);

$words = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve'];
$countWord = $words[$stats['schools']] ?? (string) $stats['schools'];
?>
<section class="ihero" aria-labelledby="ihero-title" data-parallax>
    <span class="ihero__word" aria-hidden="true">Institutions</span>
    <span class="ihero__glow" aria-hidden="true"></span>
    <div class="ihero__inner">
        <div class="ihero__copy">
            <p class="section-eyebrow ihero__fade" style="--d: 0">Our Institutions</p>
            <h1 class="ihero__title" id="ihero-title">
                <span class="ihero__line"><span style="--d: 1"><?= e($countWord) ?> schools.</span></span>
                <span class="ihero__line"><span style="--d: 2">One <em>family.</em></span></span>
            </h1>
            <p class="ihero__lead ihero__fade" style="--d: 3">
                From a farm school for girls in Mazowe to mission schools in Mhondoro and a busy high school in
                Chitungwiza, every Anglican school in the Diocese of Harare shares one calling: quality education
                rooted in Christian values.
            </p>
            <div class="ihero__actions ihero__fade" style="--d: 4">
                <a class="button" href="#directory">Explore the schools</a>
                <a class="ihero__link" href="#glance">Compare at a glance</a>
            </div>
        </div>

    </div>

    <section class="slats" aria-label="School spotlight" data-slats data-interval="4500">
        <div class="slats__row">
            <?php foreach ($schools as $i => $school):
                $photo = $school['gallery'][0] ?? null;
                $tags = array_map('trim', explode('·', $school['tag'])); ?>
                <article class="slat<?= $i === 0 ? ' is-active' : '' ?>" style="--i: <?= $i ?>" data-slat>
                    <div class="slat__media">
                        <?php if ($photo): ?>
                            <?= picture_img($photo['image'], $photo['caption'], '(min-width: 960px) 60vw, 100vw', ['loading' => $i < 3 ? 'eager' : 'lazy', 'draggable' => 'false']) ?>
                        <?php else: ?>
                            <div class="slat__monogram" aria-hidden="true"><span><?= e(school_initials($school['name'])) ?></span></div>
                        <?php endif; ?>
                    </div>

                    <button class="slat__trigger" type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" data-slat-trigger>
                        <span class="slat__n"><?= sprintf('%02d', $i + 1) ?></span>
                        <span class="slat__label"><?= e(preg_replace(['/,.*$/', '/ (Girls )?High School$/', '/ School$/'], '', $school['name'])) ?></span>
                    </button>

                    <div class="slat__info">
                        <span class="slat__tag"><?= e($tags[0]) ?></span>
                        <h2 class="slat__name"><?= e($school['name']) ?></h2>
                        <p class="slat__place"><?= e($school['place']) ?></p>
                        <a class="slat__link" href="#<?= e($school['id']) ?>" tabindex="<?= $i === 0 ? '0' : '-1' ?>">
                            View school
                            <span><svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                    </div>
                    <span class="slat__progress" aria-hidden="true"><span></span></span>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="slats__badge" aria-hidden="true">
            <svg class="slats__ring" viewBox="0 0 200 200">
                <defs><path id="badge-circle" d="M100 100m-78 0a78 78 0 1 1 156 0a78 78 0 1 1-156 0"/></defs>
                <text><textPath href="#badge-circle" textLength="486" lengthAdjust="spacing">ANGLICAN DIOCESE OF HARARE • EDUCATION •</textPath></text>
            </svg>
            <span class="slats__badge-core"><strong><?= $stats['schools'] ?></strong><span>schools</span></span>
        </div>
        <p class="visually-hidden" aria-live="polite" data-slats-status></p>
    </section>

    <dl class="istats">
        <div class="istats__item reveal" style="--delay: 0ms">
            <dt>Schools featured</dt>
            <dd><span data-count-to="<?= $stats['schools'] ?>"><?= $stats['schools'] ?></span></dd>
        </div>
        <div class="istats__item reveal" style="--delay: 90ms">
            <dt>Learners<sup>*</sup></dt>
            <dd><span data-count-to="<?= $stats['learners'] ?>"><?= number_format($stats['learners']) ?></span><em>+</em></dd>
        </div>
        <div class="istats__item reveal" style="--delay: 180ms">
            <dt>Teachers<sup>*</sup></dt>
            <dd><span data-count-to="<?= $stats['teachers'] ?>"><?= number_format($stats['teachers']) ?></span><em>+</em></dd>
        </div>
        <?php if ($stats['oldest']): ?>
            <div class="istats__item reveal" style="--delay: 270ms">
                <dt>Serving since</dt>
                <dd><?= $stats['oldest'] ?></dd>
            </div>
        <?php endif; ?>
    </dl>
    <p class="istats__note">* Totals of the schools that publish their figures; the true numbers are higher.</p>
</section>
