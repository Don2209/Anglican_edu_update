<?php
/**
 * Homepage "About" section: what the Education Department stands for (intro,
 * four pillars, headline figures) set inside a 3D tunnel of school photos.
 * Counters animate via assets/js/sections.js; the final numbers are in the
 * HTML so they are correct without JS.
 */

declare(strict_types=1);

$aboutDir = 'assets/images/about/';

$pillars = [
    [
        'icon'  => 'governance',
        'title' => 'Governance & Support',
        'text'  => 'A central body coordinating, managing and supporting every Anglican school in the Diocese of Harare.',
        'href'  => 'about.php',
    ],
    [
        'icon'  => 'faith',
        'title' => 'Christian Values',
        'text'  => 'Nurturing a strong spiritual foundation and Christian character at the heart of school life.',
        'href'  => 'about.php',
    ],
    [
        'icon'  => 'affordable',
        'title' => 'Affordable Education',
        'text'  => 'Keeping quality education within reach of families across the region.',
        'href'  => 'about.php#institutions',
    ],
    [
        'icon'  => 'sport',
        'title' => 'Sport & Culture',
        'text'  => 'Holistic development through athletics, music, heritage and the arts alongside academics.',
        'href'  => 'about.php#sports',
    ],
];

// TODO: confirm current figures with the Education Department (taken from the old site).
$stats = [
    ['value' => 20,   'suffix' => '+', 'label' => 'Schools'],
    ['value' => 1000, 'suffix' => '+', 'label' => 'Students enrolled'],
    ['value' => 14,   'suffix' => '',  'label' => 'Subjects offered'],
    ['value' => 350,  'suffix' => '+', 'label' => 'Certified teachers'],
];

$icons = [
    'governance' => '<path d="M3 21h18M5 21V10M19 21V10M9 21v-7M15 21v-7M2 10l10-6 10 6"/>',
    'faith'      => '<path d="M12 2.5v19M6 8h12"/>',
    'affordable' => '<path d="M4 10.5 12 5l8 5.5"/><path d="M6 9.5V19h12V9.5"/><path d="M10 19v-5h4v5"/>',
    'sport'      => '<circle cx="12" cy="12" r="9"/><path d="M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18M3 12h18"/>',
];

// Photo tiles that drift along the tunnel walls (decorative). 'pos' = offset across the wall.
$tunnelDir = $aboutDir . 'tunnel/';
$tunnelTiles = [
    'left'   => [['tunnel-bishop', '8%'], ['tunnel-relay-race', '52%'], ['tunnel-cooking-class', '22%']],
    'right'  => [['tunnel-senior-students', '40%'], ['tunnel-long-jump', '6%'], ['tunnel-first-aid-team', '58%']],
    'top'    => [['tunnel-classroom-lesson', '58%'], ['tunnel-school-buses', '12%'], ['tunnel-ball-catch', '40%']],
    'bottom' => [['tunnel-teacher-and-pupils', '36%'], ['tunnel-students-joy', '64%'], ['tunnel-students-parade', '10%']],
];
$tunnelDuration = 30; // seconds for one tile to travel the length of the tunnel
?>
<section class="about" id="about" aria-labelledby="about-title">
    <div class="tunnel" data-tunnel data-parallax style="--tunnel-duration: <?= $tunnelDuration ?>s">
        <div class="tunnel__scene" aria-hidden="true">
            <div class="tunnel__room">
                <?php foreach ([0.25, 0.5, 0.75, 1] as $depth): ?>
                    <span class="tunnel__frame" style="--z: <?= $depth ?>"></span>
                <?php endforeach; ?>

                <?php foreach ($tunnelTiles as $wall => $tiles): ?>
                    <div class="tunnel__wall tunnel__wall--<?= $wall ?>">
                        <?php foreach ($tiles as $i => [$image, $pos]):
                            // Spread the tiles evenly along the loop, offset per wall so they never line up.
                            $offset = ($i / count($tiles) + array_search($wall, array_keys($tunnelTiles)) * 0.08) * $tunnelDuration; ?>
                            <div class="tunnel__tile" style="--pos: <?= $pos ?>; --delay: -<?= round($offset, 2) ?>s">
                                <img src="<?= e($tunnelDir . $image . '.webp') ?>" alt="" width="480" height="360" loading="lazy" decoding="async">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="tunnel__fog"></div>
        </div>

        <div class="tunnel__content about__container">
            <header class="about__header reveal">
                <p class="section-eyebrow">About the Department</p>
                <h2 class="about__title" id="about-title">What we stand for</h2>
                <p class="about__lead">
                    The Education Department is the central body that manages, coordinates and supports every
                    Anglican school in the Diocese, empowering learners to become responsible, compassionate
                    and well-rounded individuals.
                </p>
            </header>

            <div class="framework reveal">
                <span class="framework__corner framework__corner--tl" aria-hidden="true"></span>
                <span class="framework__corner framework__corner--tr" aria-hidden="true"></span>
                <span class="framework__corner framework__corner--bl" aria-hidden="true"></span>
                <span class="framework__corner framework__corner--br" aria-hidden="true"></span>

                <ul class="framework__pillars" role="list">
                    <?php foreach ($pillars as $i => $pillar): ?>
                        <li class="framework__cell">
                            <div class="framework__meta" aria-hidden="true">
                                <span><?= sprintf('%02d', $i + 1) ?></span>
                                <svg class="framework__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?= $icons[$pillar['icon']] ?></svg>
                            </div>
                            <h3 class="framework__title">
                                <a href="<?= e($pillar['href']) ?>"><?= e($pillar['title']) ?></a>
                            </h3>
                            <p class="framework__text"><?= e($pillar['text']) ?></p>
                            <svg class="framework__arrow" aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <dl class="framework__stats">
                    <?php foreach ($stats as $stat): ?>
                        <div class="framework__stat">
                            <dt class="framework__label"><?= e($stat['label']) ?></dt>
                            <dd class="framework__value">
                                <span data-count-to="<?= (int) $stat['value'] ?>"><?= number_format($stat['value']) ?></span><span class="framework__suffix"><?= e($stat['suffix']) ?></span>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </div>
    </div>
</section>
