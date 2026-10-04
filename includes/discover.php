<?php
/**
 * Homepage "Discover More" section: a carousel of photo cards where the active
 * card opens into a text panel. Story-style progress bars double as the
 * autoplay timer and as navigation. Behaviour: assets/js/discover.js.
 * Without JS the cards render as a horizontally scrollable row with every
 * panel open.
 */

declare(strict_types=1);

$discoverDir = 'assets/images/discover/';

$discoverItems = [
    [
        'image' => 'bishop',
        'alt'   => 'The Bishop of Harare smiling in his mitre and vestments at a pulpit',
        'title' => 'Our Bishop',
        'text'  => 'An Anglican Bishop of Harare, Rt Rev Dr. F. Mutamiri, was appointed in 2019. Born on 8 October 1968, he worked as a production line supervisor before becoming a priest. He trained for ordination at Bishop Gaul Theological College in Harare, was ordained deacon in 1998 and priest in 1991, and is a former Dean of St Mary and All Angels Cathedral.',
        'href'  => 'about.php#overview',
    ],
    [
        'image' => 'school-profiles',
        'alt'   => 'A two-storey Anglican school building beside a wide lawn',
        'title' => 'School Profiles',
        'text'  => 'The Anglican Diocese of Harare Education Department plays a vital role in the provision of quality education within Harare. With a focus on academic excellence, affordability, sports and cultural development, and Christian values, the department aims to empower students with the necessary knowledge, skills, and values to become responsible. Educating the nation is a calling.',
        'href'  => 'about.php#institutions',
    ],
    [
        'image' => 'admissions',
        'alt'   => 'Students in red school blazers standing together outdoors',
        'title' => 'Admissions',
        'text'  => 'It\'s important to note that each individual Anglican school may have specific admission criteria, deadlines, and procedures. Therefore, it\'s advisable to consult the official website or contact the specific school(s) you are interested in to obtain accurate and up-to-date information regarding their student admissions process.',
        'href'  => 'about.php#admissions',
    ],
    [
        'image' => 'news-events',
        'alt'   => 'Students in navy uniforms gathered on the steps of the Parliament building',
        'title' => 'News & Events',
        'text'  => 'To stay informed about the news and updates regarding the Anglican Diocese of Harare and its education department, we recommend you follow the official social media accounts of the Anglican Diocese of Harare and its affiliated schools. We use platforms such as Facebook and Instagram to share news, events, and updates with our followers.',
        'href'  => 'news.php',
    ],
    [
        'image' => 'academics',
        'alt'   => 'Students in white lab coats outside a science block',
        'title' => 'Academics',
        'text'  => 'Curriculum: Anglican schools often follow a curriculum that adheres to national educational standards while incorporating additional subjects and activities that reflect the school\'s values and mission. This typically includes a comprehensive academic curriculum covering subjects such as mathematics, science, languages (e.g., English, local languages), social studies, arts, and physical education.',
        'href'  => 'about.php#academics',
    ],
    [
        'image' => 'sports-culture',
        'alt'   => 'Learners sprinting down a grass running track on sports day',
        'title' => 'Sports & Culture',
        'text'  => 'The specific sports and cultural activities offered by each Anglican school may vary. Anglican schools often provide a variety of sports options to encourage physical fitness and healthy competition. Common sports offered may include soccer (football), cricket, basketball, netball, athletics (track and field) and volleyball, depending on each school\'s facilities and resources.',
        'href'  => 'about.php#sports',
    ],
];
$discoverCount = count($discoverItems);

/** srcset from the actual widths on disk (small sources were never upscaled). */
$discoverSrcset = static function (string $image) use ($discoverDir): string {
    $sources = [];
    foreach ([400, 720] as $size) {
        $path = "{$discoverDir}discover-{$image}-{$size}.webp";
        $info = @getimagesize(dirname(__DIR__) . '/' . $path);
        if ($info) {
            $sources[$info[0]] = "{$path} {$info[0]}w";
        }
    }
    ksort($sources);

    return e(implode(', ', $sources));
};

$arrowIcon = '<svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section class="discover" id="discover" aria-labelledby="discover-title" aria-roledescription="carousel" data-discover data-interval="7000" data-parallax>
    <div class="discover__map" aria-hidden="true"></div>

    <header class="discover__header">
        <h2 class="discover__heading split" id="discover-title">
            <?php foreach (['Discover', 'More'] as $w => $word): ?>
                <span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span>
            <?php endforeach; ?>
        </h2>
        <p class="discover__lead reveal" style="--delay: 250ms">Explore what we have to offer.</p>
    </header>

    <div class="discover__bar-row" hidden data-discover-ui>
        <div class="discover__bars" role="group" aria-label="Choose a topic">
            <?php foreach ($discoverItems as $i => $item): ?>
                <button class="discover__bar<?= $i === 0 ? ' is-active' : '' ?>" type="button"
                        aria-controls="discover-<?= e($item['image']) ?>"
                        <?= $i === 0 ? 'aria-current="true"' : '' ?>
                        data-discover-bar="<?= $i ?>">
                    <span class="discover__bar-fill"></span>
                    <span class="visually-hidden"><?= e($item['title']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="discover__controls">
            <button class="discover__control" type="button" data-discover-prev>
                <svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="visually-hidden">Previous topic</span>
            </button>
            <button class="discover__control" type="button" aria-pressed="false" data-discover-pause>
                <svg class="discover__icon-pause" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                <svg class="discover__icon-play" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                <span class="visually-hidden">Pause</span>
            </button>
            <button class="discover__control" type="button" data-discover-next>
                <?= $arrowIcon ?>
                <span class="visually-hidden">Next topic</span>
            </button>
        </div>
    </div>

    <div class="discover__viewport" data-discover-viewport>
        <div class="discover__track" data-discover-track>
            <?php foreach ($discoverItems as $i => $item): ?>
                <div class="discover__item<?= $i === 0 ? ' is-active' : '' ?>" id="discover-<?= e($item['image']) ?>"
                    role="group" aria-roledescription="slide" aria-label="<?= $i + 1 ?> of <?= $discoverCount ?>: <?= e($item['title']) ?>"
                    data-discover-item>
                    <div class="discover__panel" data-discover-panel>
                        <div class="discover__panel-inner">
                            <p class="discover__count" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?> / <?= sprintf('%02d', $discoverCount) ?></p>
                            <h3 class="discover__title"><?= e($item['title']) ?></h3>
                            <p class="discover__text"><?= e($item['text']) ?></p>
                            <a class="discover__cta" href="<?= e($item['href']) ?>">
                                Read more<span class="visually-hidden"> about <?= e($item['title']) ?></span>
                                <span class="discover__cta-arrow"><?= $arrowIcon ?></span>
                            </a>
                        </div>
                    </div>

                    <button class="discover__photo" type="button" data-discover-select="<?= $i ?>">
                        <img src="<?= e($discoverDir . 'discover-' . $item['image'] . '-400.webp') ?>"
                             srcset="<?= $discoverSrcset($item['image']) ?>"
                             sizes="(min-width: 640px) 340px, 82vw"
                             width="400" height="500"
                             alt="<?= e($item['alt']) ?>"
                             loading="lazy" decoding="async">
                        <span class="discover__label" aria-hidden="true"><?= e($item['title']) ?></span>
                        <span class="visually-hidden">Show <?= e($item['title']) ?></span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a class="discover__contact reveal" href="tel:<?= e(SITE_PHONE) ?>">
        <span class="discover__contact-dot" aria-hidden="true"></span>
        Talk to our office
        <span class="discover__contact-number"><?= e(SITE_PHONE_DISPLAY) ?></span>
    </a>
</section>
