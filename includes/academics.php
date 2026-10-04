<?php
/**
 * Homepage "Academic Offers" section: one card per level that pins and stacks
 * as you scroll (the card underneath shrinks back as the next slides over it).
 * Subject chips pop in when a card arrives; a spotlight follows the cursor.
 * Behaviour: assets/js/sections.js. Without JS the cards simply stack.
 */

declare(strict_types=1);

$levelDir = 'assets/images/academics/';

// Abbreviations get a full-name tooltip via <abbr>
$subjectNames = [
    'FAREME' => 'Family, Religion and Moral Education',
    'FRS'    => 'Family and Religious Studies',
];

$levels = [
    [
        'key'      => 'primary',
        'title'    => 'Primary Level',
        'tagline'  => 'Strong foundations in literacy, numeracy and values.',
        'subjects' => ['Mathematics', 'English', 'Shona', 'FAREME', 'Physical Education', 'Agriculture'],
        'alt'      => 'A teacher smiling with young pupils in blue uniforms during an outdoor activity',
        'href'     => 'about.php#primary',
    ],
    [
        'key'      => 'ordinary',
        'title'    => 'Ordinary Level',
        'tagline'  => 'A broad curriculum across the sciences, humanities and commerce.',
        'subjects' => ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'History', 'Accounting'],
        'alt'      => 'Students in white lab coats carrying out experiments in a chemistry laboratory',
        'href'     => 'about.php#ordinary',
    ],
    [
        'key'      => 'advanced',
        'title'    => 'Advanced Level',
        'tagline'  => 'Specialised study that prepares learners for university and careers.',
        'subjects' => ['Biology', 'Geography', 'Physics', 'FRS', 'History', 'Pure Mathematics'],
        'alt'      => 'A teacher writing on a whiteboard in front of senior students',
        'href'     => 'about.php#advanced',
    ],
];

$levelSrcset = static function (string $key) use ($levelDir): string {
    $sources = [];
    foreach ([480, 800] as $size) {
        $path = "{$levelDir}level-{$key}-{$size}.webp";
        $info = @getimagesize(dirname(__DIR__) . '/' . $path);
        if ($info) {
            $sources[$info[0]] = "{$path} {$info[0]}w";
        }
    }
    ksort($sources);

    return e(implode(', ', $sources));
};

$arrow = '<svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section class="levels" id="academics" aria-labelledby="levels-title">
    <div class="levels__container">
        <header class="levels__header">
            <p class="section-eyebrow reveal">Academics</p>
            <h2 class="levels__heading split" id="levels-title">
                <?php foreach (['Academic', 'Offers', 'From', 'Our', 'Institutes'] as $w => $word): ?>
                    <span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span>
                <?php endforeach; ?>
            </h2>
            <p class="levels__lead reveal" style="--delay: 300ms">
                Schools within the Anglican Diocese of Harare Education Department offer a range of academic
                programs designed to provide students with a well-rounded education.
            </p>
        </header>

        <div class="levels__stack" data-stack>
            <?php foreach ($levels as $i => $level): ?>
                <article class="level level--<?= e($level['key']) ?>" style="--i: <?= $i ?>" aria-labelledby="level-<?= e($level['key']) ?>" data-stack-card data-spotlight>
                    <div class="level__body">
                        <span class="level__number" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                        <p class="level__kicker">Level <?= sprintf('%02d', $i + 1) ?> <span>/ <?= sprintf('%02d', count($levels)) ?></span></p>
                        <h3 class="level__title" id="level-<?= e($level['key']) ?>"><?= e($level['title']) ?></h3>
                        <p class="level__tagline"><?= e($level['tagline']) ?></p>

                        <ul class="level__subjects stagger" role="list" aria-label="Subjects include">
                            <?php foreach ($level['subjects'] as $s => $subject): ?>
                                <li class="level__chip" style="--i: <?= $s ?>">
                                    <?php if (isset($subjectNames[$subject])): ?>
                                        <abbr title="<?= e($subjectNames[$subject]) ?>"><?= e($subject) ?></abbr>
                                    <?php else: ?>
                                        <?= e($subject) ?>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <li class="level__chip level__chip--more" style="--i: <?= count($level['subjects']) ?>" aria-hidden="true">+ more</li>
                        </ul>

                        <a class="level__cta" href="<?= e($level['href']) ?>">
                            See more<span class="visually-hidden"> <?= e($level['title']) ?> subjects</span>
                            <span class="level__cta-arrow"><?= $arrow ?></span>
                        </a>
                    </div>

                    <figure class="level__media">
                        <img src="<?= e($levelDir . 'level-' . $level['key'] . '-480.webp') ?>"
                             srcset="<?= $levelSrcset($level['key']) ?>"
                             sizes="(min-width: 860px) 420px, 90vw"
                             width="480" height="600"
                             alt="<?= e($level['alt']) ?>"
                             loading="lazy" decoding="async">
                    </figure>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
