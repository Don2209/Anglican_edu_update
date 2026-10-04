<?php
/**
 * The four chapters as tabs. Tab switches play a "saltire wipe": the crest's
 * red X bursts open across the screen and the new chapter appears behind it.
 * Behaviour: assets/js/about.js.
 * Without JS the tab bar stays hidden and all chapters are shown in order.
 *
 * @var array $data
 */

declare(strict_types=1);

$chapterKeys = array_keys($data['chapters']);
?>
<div class="chapters" data-chapters>
    <div class="chapters__bar" hidden data-chapters-bar>
        <div class="chapters__tabs" role="tablist" aria-label="About chapters">
            <span class="chapters__indicator" aria-hidden="true" data-tab-indicator></span>
            <?php foreach ($data['chapters'] as $key => $chapter): ?>
                <button class="chapters__tab" type="button" role="tab" id="tab-<?= $key ?>"
                        aria-controls="<?= $key ?>" aria-selected="false" tabindex="-1" data-tab="<?= $key ?>">
                    <span class="chapters__tab-n" aria-hidden="true"><?= e($chapter['n']) ?></span>
                    <span class="chapters__tab-label"><?= e($chapter['title']) ?></span>
                    <span class="chapters__tab-short" aria-hidden="true"><?= e($chapter['short']) ?></span>
                    <span class="chapters__tab-progress" aria-hidden="true"></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    foreach ($chapterKeys as $index => $key) {
        $chapter = $data['chapters'][$key];
        $nextKey = $chapterKeys[($index + 1) % count($chapterKeys)];
        $next = $data['chapters'][$nextKey];
        ?>
        <section class="chapter chapter--<?= $key ?>" id="<?= $key ?>" role="tabpanel" aria-labelledby="chapter-title-<?= $key ?>" data-panel="<?= $key ?>">
            <header class="chapter__banner">
                <span class="chapter__numeral" aria-hidden="true"><?= e($chapter['n']) ?></span>
                <div class="chapter__heading">
                    <p class="section-eyebrow">Chapter <?= e($chapter['n']) ?></p>
                    <h2 class="chapter__title" id="chapter-title-<?= $key ?>"><?= e($chapter['title']) ?></h2>
                </div>
                <figure class="chapter__photo">
                    <?= picture_img($chapter['image'], '', '(min-width: 900px) 45vw, 100vw', ['loading' => $index === 0 ? 'eager' : 'lazy']) ?>
                </figure>
            </header>

            <?php require __DIR__ . "/chapter-{$key}.php"; ?>

            <footer class="chapter__next">
                <span>Next chapter</span>
                <a class="chapter__next-link" href="#<?= $nextKey ?>" data-chapter-link="<?= $nextKey ?>">
                    <span class="chapter__next-n"><?= e($next['n']) ?></span>
                    <?= e($next['title']) ?>
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </footer>
        </section>
    <?php } ?>

    <div class="saltire-wipe" aria-hidden="true" data-wipe></div>
    <p class="visually-hidden" aria-live="polite" data-chapter-status></p>
</div>
