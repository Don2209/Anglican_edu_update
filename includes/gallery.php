<?php
/**
 * Homepage gallery: a bento grid with category filters and a lightbox.
 * - Tiles unveil with a clip-path reveal and drift inside their frames (parallax).
 * - Filtering and opening a photo morph smoothly via the View Transitions API
 *   where supported (assets/js/gallery.js); otherwise they simply update.
 * Without JS every tile is a plain link to its full-size photo.
 */

declare(strict_types=1);

$galleryDir = 'assets/images/gallery/';

$galleryCategories = [
    'academics' => 'Academics',
    'sports'    => 'Sports',
    'culture'   => 'Culture',
    'projects'  => 'Projects',
    'community' => 'Community',
];

// 'size' sets the bento tile shape: feature (2x2), tall (1x2), wide (2x1) or small (1x1)
$galleryPhotos = [
    ['image' => 'high-jump',       'category' => 'sports',    'size' => 'feature', 'caption' => 'Clearing the bar at inter-house athletics'],
    ['image' => 'exhibition',      'category' => 'academics', 'size' => 'tall',    'caption' => 'Learners at the Migrant Archives and Artifacts programme'],
    ['image' => 'farm-project',    'category' => 'projects',  'size' => 'small',   'caption' => 'Crops from the school agriculture project'],
    ['image' => 'science-lab',     'category' => 'academics', 'size' => 'small',   'caption' => 'Practical work in the science laboratory'],
    ['image' => 'marimba',         'category' => 'culture',   'size' => 'wide',    'caption' => 'Marimba practice in the music room'],
    ['image' => 'weather-station', 'category' => 'projects',  'size' => 'small',   'caption' => 'The school weather station'],
    ['image' => 'heritage-huts',   'category' => 'culture',   'size' => 'small',   'caption' => 'Traditional heritage huts'],
    ['image' => 'celebration',     'category' => 'community', 'size' => 'wide',    'caption' => 'Celebrating together after a school event'],
    ['image' => 'sprint',          'category' => 'sports',    'size' => 'small',   'caption' => 'The sprint final on sports day'],
    ['image' => 'student-meeting', 'category' => 'community', 'size' => 'small',   'caption' => 'Student leaders in discussion'],
];

$categoryCounts = array_count_values(array_column($galleryPhotos, 'category'));

/** Real pixel size of each file, for srcset and width/height (prevents layout shift). */
$galleryImage = static function (string $image, int $size) use ($galleryDir): array {
    $path = "{$galleryDir}gallery-{$image}-{$size}.webp";
    $info = @getimagesize(dirname(__DIR__) . '/' . $path) ?: [$size, round($size * 0.75)];

    return ['src' => $path, 'w' => $info[0], 'h' => $info[1]];
};
?>
<section class="gallery" id="gallery" aria-labelledby="gallery-title">
    <div class="gallery__container">
        <header class="gallery__header">
            <div>
                <p class="section-eyebrow reveal">Gallery</p>
                <h2 class="gallery__heading split" id="gallery-title">
                    <?php foreach (['Moments', 'From', 'Our', 'Schools'] as $w => $word): ?>
                        <span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span>
                    <?php endforeach; ?>
                </h2>
            </div>
            <a class="gallery__all reveal" href="gallery.php">
                View full gallery
                <span class="gallery__all-arrow"><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </a>
        </header>

        <div class="gallery__filters reveal" role="group" aria-label="Filter photos by category" hidden data-gallery-filters>
            <button class="gallery__filter" type="button" aria-pressed="true" data-filter="all">
                All <span class="gallery__count"><?= count($galleryPhotos) ?></span>
            </button>
            <?php foreach ($galleryCategories as $key => $label): ?>
                <button class="gallery__filter" type="button" aria-pressed="false" data-filter="<?= e($key) ?>">
                    <?= e($label) ?> <span class="gallery__count"><?= (int) ($categoryCounts[$key] ?? 0) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <ul class="gallery__grid" role="list" data-gallery-grid>
            <?php foreach ($galleryPhotos as $i => $photo):
                $small = $galleryImage($photo['image'], 640);
                $large = $galleryImage($photo['image'], 1600); ?>
                <li class="gallery__item gallery__item--<?= e($photo['size']) ?> reveal-clip" style="--i: <?= $i ?>"
                    data-category="<?= e($photo['category']) ?>" data-gallery-item>
                    <a class="gallery__tile" href="<?= e($large['src']) ?>" data-parallax
                       data-gallery-open="<?= $i ?>"
                       data-full="<?= e($large['src']) ?>" data-full-w="<?= $large['w'] ?>" data-full-h="<?= $large['h'] ?>"
                       data-caption="<?= e($photo['caption']) ?>"
                       data-category-label="<?= e($galleryCategories[$photo['category']]) ?>">
                        <span class="gallery__frame">
                            <img src="<?= e($small['src']) ?>"
                                 srcset="<?= e($small['src'] . ' ' . $small['w'] . 'w, ' . $large['src'] . ' ' . $large['w'] . 'w') ?>"
                                 sizes="<?= $photo['size'] === 'feature' ? '(min-width: 900px) 50vw, 100vw' : '(min-width: 900px) 25vw, 50vw' ?>"
                                 width="<?= $small['w'] ?>" height="<?= $small['h'] ?>"
                                 alt="<?= e($photo['caption']) ?>"
                                 loading="lazy" decoding="async">
                        </span>
                        <span class="gallery__meta" aria-hidden="true">
                            <span class="gallery__tag"><?= e($galleryCategories[$photo['category']]) ?></span>
                            <span class="gallery__caption"><?= e($photo['caption']) ?></span>
                        </span>
                        <span class="gallery__zoom" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="visually-hidden" aria-live="polite" data-gallery-status></p>
    </div>

    <dialog class="lightbox" aria-label="Photo viewer" data-lightbox>
        <figure class="lightbox__figure">
            <img class="lightbox__img" src="data:," alt="" data-lightbox-img>
            <figcaption class="lightbox__caption">
                <span class="lightbox__tag" data-lightbox-tag></span>
                <span class="lightbox__text" data-lightbox-text></span>
                <span class="lightbox__count" data-lightbox-count></span>
            </figcaption>
        </figure>
        <button class="lightbox__btn lightbox__close" type="button" data-lightbox-close>
            <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span class="visually-hidden">Close</span>
        </button>
        <button class="lightbox__btn lightbox__prev" type="button" data-lightbox-prev>
            <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span class="visually-hidden">Previous photo</span>
        </button>
        <button class="lightbox__btn lightbox__next" type="button" data-lightbox-next>
            <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span class="visually-hidden">Next photo</span>
        </button>
    </dialog>
</section>
