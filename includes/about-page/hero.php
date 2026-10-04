<?php
/**
 * About hero: the "Shield Navigator". The diocesan shield is divided by its
 * red saltire into four quadrants; each quadrant is a photo doorway into one
 * chapter (clockwise: Institutions, Academics, Sports & Culture, Projects).
 *
 * @var array $data   from data.php
 */

declare(strict_types=1);

$shieldPath = 'M10 10H390V222C390 338 300 410 200 452C100 410 10 338 10 222Z';
// Quadrant triangles meet where the saltire crosses (200, 231)
$quadrants = [
    'institutions' => ['points' => '0,0 400,0 200,231',     'label' => [200, 92],  'img' => [40, 0, 320, 240]],
    'academics'    => ['points' => '400,0 400,462 200,231', 'label' => [318, 238], 'img' => [190, 20, 210, 420]],
    'sports'       => ['points' => '400,462 0,462 200,231', 'label' => [200, 372], 'img' => [40, 220, 320, 242]],
    'projects'     => ['points' => '0,462 0,0 200,231',     'label' => [82, 238],  'img' => [0, 20, 210, 420]],
];
$schoolCount = count($data['schools']);
$subjectCount = count(array_unique(array_merge(...array_column($data['subjects'], 'list'))));
?>
<section class="ahero" id="overview" aria-labelledby="about-title">
    <div class="ahero__inner">
        <div class="ahero__copy">
            <p class="section-eyebrow ahero__reveal" style="--d: 0">About Us</p>
            <h1 class="ahero__title" id="about-title">
                <span class="ahero__line"><span style="--d: 1">One shield.</span></span>
                <span class="ahero__line"><span style="--d: 2">Four <em>callings.</em></span></span>
            </h1>
            <p class="ahero__lead ahero__reveal" style="--d: 3">
                The Anglican Diocese of Harare Education Department oversees every Anglican school in the
                diocese, from primary to secondary, under the leadership of the Rt Rev Dr Farai Mutamiri.
                Choose a quarter of our shield to explore.
            </p>

            <dl class="ahero__stats ahero__reveal" style="--d: 4">
                <div><dt>Featured schools</dt><dd><?= $schoolCount ?></dd></div>
                <div><dt>Subjects across levels</dt><dd><?= $subjectCount ?></dd></div>
                <div><dt>Flagship projects</dt><dd><?= count($data['projects']) ?></dd></div>
            </dl>
        </div>

        <div class="shield" data-shield>
            <svg class="shield__svg" viewBox="0 0 400 462" role="group" aria-label="Explore the four chapters">
                <defs>
                    <clipPath id="shield-clip"><path d="<?= $shieldPath ?>"/></clipPath>
                    <?php foreach ($quadrants as $key => $q): ?>
                        <clipPath id="q-<?= $key ?>"><polygon points="<?= $q['points'] ?>"/></clipPath>
                    <?php endforeach; ?>
                </defs>

                <g clip-path="url(#shield-clip)">
                    <rect width="400" height="462" fill="#141414"/>
                    <?php foreach ($quadrants as $key => $q):
                        $chapter = $data['chapters'][$key];
                        $photo = responsive_image($chapter['image']); ?>
                        <a class="shield__q shield__q--<?= $key ?>" href="#<?= $key ?>" data-chapter-link="<?= $key ?>"
                           aria-label="<?= e($chapter['n'] . ' ' . $chapter['title']) ?>">
                            <g clip-path="url(#q-<?= $key ?>)">
                                <image class="shield__img" href="<?= e($photo['src']) ?>"
                                       x="<?= $q['img'][0] ?>" y="<?= $q['img'][1] ?>" width="<?= $q['img'][2] ?>" height="<?= $q['img'][3] ?>"
                                       preserveAspectRatio="xMidYMid slice"/>
                                <polygon class="shield__tint" points="<?= $q['points'] ?>"/>
                            </g>
                            <text class="shield__num" x="<?= $q['label'][0] ?>" y="<?= $q['label'][1] - 14 ?>" text-anchor="middle"><?= e($chapter['n']) ?></text>
                            <text class="shield__label" x="<?= $q['label'][0] ?>" y="<?= $q['label'][1] + 8 ?>" text-anchor="middle"><?= e($chapter['short']) ?></text>
                        </a>
                    <?php endforeach; ?>

                    <!-- The saltire: white edge under crest red, drawn on load -->
                    <g class="shield__saltire" aria-hidden="true">
                        <path class="shield__band shield__band--edge" d="M-10 -12L410 474M410 -12L-10 474" pathLength="1"/>
                        <path class="shield__band" d="M-10 -12L410 474M410 -12L-10 474" pathLength="1"/>
                    </g>
                </g>

                <path class="shield__outline" d="<?= $shieldPath ?>" pathLength="1" aria-hidden="true"/>

                <!-- Centre medallion shows the quadrant being pointed at -->
                <g class="shield__medal" aria-hidden="true">
                    <circle cx="200" cy="231" r="46"/>
                    <text class="shield__medal-num" x="200" y="226" text-anchor="middle" data-shield-num>✦</text>
                    <text class="shield__medal-text" x="200" y="246" text-anchor="middle" data-shield-text>Explore</text>
                </g>
            </svg>
            <p class="shield__hint" aria-hidden="true">Tap a quarter to open its chapter</p>
        </div>
    </div>

    <!-- What the department stands for: expanding panels -->
    <div class="values" role="list">
        <?php foreach ($data['values'] as $i => $value): ?>
            <article class="values__item reveal" role="listitem" style="--delay: <?= $i * 100 ?>ms" tabindex="0">
                <span class="values__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                <h2 class="values__title"><?= e($value['title']) ?></h2>
                <p class="values__text"><?= e($value['text']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
