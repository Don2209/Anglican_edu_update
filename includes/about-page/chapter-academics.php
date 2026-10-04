<?php
/**
 * Chapter 02: Academics. What learning looks like (bento of six features) and
 * a subject explorer: switch level or search across every level at once.
 *
 * @var array $data
 */

declare(strict_types=1);

$featureIcons = [
    'book'  => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 21V5M8 7h7"/>',
    'spark' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6"/>',
    'path'  => '<circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h6a4 4 0 0 0 0-8h-4a4 4 0 0 1 0-8h6"/>',
    'cross' => '<path d="M12 3v18M7 8h10"/>',
    'chip'  => '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4"/>',
    'hand'  => '<path d="M7 11V6a2 2 0 0 1 4 0v4M11 10V4a2 2 0 0 1 4 0v6M15 9a2 2 0 0 1 4 0v5a7 7 0 0 1-7 7h-1a6 6 0 0 1-5-3l-2-4a2 2 0 0 1 3-2l2 2"/>',
];
$abbr = $data['abbreviations'];
?>
<div class="chapter__body">
    <div class="chapter__intro reveal">
        <p class="chapter__lead">
            A range of academic programmes designed to give every student a well-rounded education, following
            national standards while reflecting each school's values and mission.
        </p>
    </div>

    <ul class="bento" role="list">
        <?php foreach ($data['features'] as $i => $feature): ?>
            <li class="bento__card bento__card--<?= $i ?> reveal" style="--delay: <?= $i * 70 ?>ms" data-spotlight>
                <span class="bento__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?= $featureIcons[$feature['icon']] ?></svg>
                </span>
                <h3 class="bento__title"><?= e($feature['title']) ?></h3>
                <p class="bento__text"><?= e($feature['text']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <section class="explorer reveal" aria-labelledby="explorer-title" data-explorer>
        <header class="explorer__header">
            <div>
                <p class="section-eyebrow">Subjects</p>
                <h3 class="explorer__title" id="explorer-title">Find your subjects</h3>
            </div>
            <div class="explorer__controls" hidden data-explorer-controls>
                <div class="seg" role="group" aria-label="Choose a level">
                    <?php $first = true; foreach ($data['subjects'] as $key => $level): ?>
                        <button type="button" aria-pressed="<?= $first ? 'true' : 'false' ?>" data-explorer-level="<?= $key ?>">
                            <?= e($level['label']) ?> <span><?= count($level['list']) ?></span>
                        </button>
                    <?php $first = false; endforeach; ?>
                </div>
                <label class="explorer__search">
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <span class="visually-hidden">Search subjects across all levels</span>
                    <input type="search" placeholder="Search all subjects…" autocomplete="off" data-explorer-search>
                </label>
            </div>
        </header>

        <?php foreach ($data['subjects'] as $key => $level): ?>
            <div class="explorer__group" id="<?= $key ?>" data-explorer-group="<?= $key ?>">
                <h4 class="explorer__group-title"><?= e($level['label']) ?> <span>· <span data-group-count><?= count($level['list']) ?></span> subjects</span></h4>
                <ul class="explorer__chips" role="list">
                    <?php foreach ($level['list'] as $s => $subject): ?>
                        <li class="explorer__chip" style="--i: <?= $s ?>" data-subject="<?= e(strtolower($subject . ' ' . ($abbr[$subject] ?? ''))) ?>">
                            <?php if (isset($abbr[$subject])): ?>
                                <abbr title="<?= e($abbr[$subject]) ?>"><?= e($subject) ?></abbr>
                            <?php else: ?>
                                <?= e($subject) ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        <p class="explorer__empty" hidden data-explorer-empty>No subjects match that search.</p>
        <p class="visually-hidden" aria-live="polite" data-explorer-status></p>
    </section>
</div>
