<?php
/**
 * Chapter 03: Sports & Culture. Two counter-scrolling rows of disciplines,
 * event "tickets" and a curved 3D photo reel (assets/js/about.js).
 *
 * @var array $data
 */

declare(strict_types=1);
?>
<div class="chapter__body">
    <div class="chapter__intro reveal">
        <p class="chapter__lead">
            Holistic development means more than the classroom. Clubs, societies and programmes in sport,
            arts, music, drama, debate, public speaking, community service and leadership help students
            explore their interests, develop talents and build teamwork.
        </p>
    </div>
</div>

<div class="disciplines" aria-label="Sports and cultural activities">
    <?php foreach (['sports' => 'Sports we play', 'culture' => 'Culture we celebrate'] as $key => $label): ?>
        <div class="disciplines__row disciplines__row--<?= $key ?>">
            <h3 class="visually-hidden"><?= e($label) ?></h3>
            <ul class="visually-hidden" role="list">
                <?php foreach ($data[$key] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
            </ul>
            <div class="disciplines__track" aria-hidden="true">
                <?php for ($copy = 0; $copy < 2; $copy++): ?>
                    <div class="disciplines__set">
                        <span class="disciplines__label"><?= e($label) ?></span>
                        <?php foreach ($data[$key] as $item): ?>
                            <span class="disciplines__word"><?= e($item) ?></span><span class="disciplines__dot">✦</span>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="chapter__body">
    <div class="events">
        <?php foreach ($data['events'] as $i => $event): ?>
            <article class="ticket reveal" style="--delay: <?= $i * 120 ?>ms">
                <div class="ticket__stub">
                    <span class="ticket__day"><?= e($event['day']) ?></span>
                    <span class="ticket__month"><?= e($event['month']) ?></span>
                    <span class="ticket__year"><?= e($event['year']) ?></span>
                </div>
                <div class="ticket__body">
                    <p class="ticket__place">
                        <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                        <?= e($event['place']) ?>
                    </p>
                    <h3 class="ticket__title"><?= e($event['title']) ?></h3>
                    <p class="ticket__text"><?= e($event['text']) ?></p>
                </div>
                <figure class="ticket__photo">
                    <?= picture_img($event['image'], $event['alt'], '(min-width: 900px) 260px, 100vw') ?>
                </figure>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<section class="reel" aria-labelledby="reel-title" aria-roledescription="carousel" data-reel>
    <header class="reel__header">
        <div>
            <p class="section-eyebrow">In pictures</p>
            <h3 class="reel__title" id="reel-title">Moments on the field and stage</h3>
        </div>
        <div class="reel__controls" hidden data-reel-controls>
            <p class="reel__count" aria-hidden="true"><span data-reel-current>01</span><span class="reel__count-total"> / <?= sprintf('%02d', count($data['strip'])) ?></span></p>
            <button class="reel__btn" type="button" data-reel-prev>
                <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="visually-hidden">Previous photo</span>
            </button>
            <button class="reel__btn reel__btn--play" type="button" aria-pressed="false" data-reel-pause>
                <svg class="reel__icon-pause" aria-hidden="true" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                <svg class="reel__icon-play" aria-hidden="true" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                <span class="visually-hidden">Pause slideshow</span>
            </button>
            <button class="reel__btn" type="button" data-reel-next>
                <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="visually-hidden">Next photo</span>
            </button>
        </div>
    </header>

    <div class="reel__stage" tabindex="0" aria-label="Photos: drag, swipe or use the arrow keys" data-reel-stage>
        <div class="reel__track" data-reel-track>
            <?php foreach ($data['strip'] as $i => $photo): ?>
                <div class="reel__card" role="group" aria-roledescription="slide" aria-label="<?= $i + 1 ?> of <?= count($data['strip']) ?>: <?= e($photo['caption']) ?>" data-reel-card>
                    <figure class="reel__figure">
                        <?= picture_img($photo['image'], $photo['alt'], '(min-width: 900px) 380px, 70vw', ['draggable' => 'false']) ?>
                        <figcaption class="reel__caption">
                            <span class="reel__tag"><?= e($photo['tag']) ?></span>
                            <span class="reel__text"><?= e($photo['caption']) ?></span>
                        </figcaption>
                    </figure>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="reel__footer" hidden data-reel-footer>
        <div class="reel__progress" aria-hidden="true"><span data-reel-progress></span></div>
        <p class="reel__hint" aria-hidden="true">
            <span class="reel__hint-icon"></span> Drag, swipe or use the arrows
        </p>
    </div>
    <p class="visually-hidden" aria-live="polite" data-reel-status></p>
</section>
