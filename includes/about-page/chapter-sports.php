<?php
/**
 * Chapter 03: Sports & Culture. Two counter-scrolling rows of disciplines,
 * event "tickets" and a draggable photo strip.
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

<div class="strip" data-strip>
    <ul class="strip__track" role="list" tabindex="0" aria-label="Sports and culture photos (scroll sideways)">
        <?php foreach ($data['strip'] as $photo): ?>
            <li class="strip__item">
                <?= picture_img($photo['image'], $photo['alt'], '(min-width: 900px) 420px, 78vw', ['draggable' => 'false']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <p class="strip__hint" aria-hidden="true">Drag to explore ⟷</p>
</div>
