<?php
/**
 * Chapter 04: Projects, as an auto-playing showcase. A numbered list of all
 * projects (the current one fills a progress bar) sits beside a large photo
 * that wipes to the next project. Behaviour: assets/js/about.js.
 * Without JS every project is listed in full, each with its own photo.
 *
 * @var array $data
 */

declare(strict_types=1);

$projectCount = count($data['projects']);
?>
<div class="chapter__body">
    <div class="chapter__intro reveal">
        <p class="chapter__lead">
            Hands-on projects that prepare industry-ready students and help our schools become self-reliant,
            from green energy to agribusiness and technology.
        </p>
    </div>

    <section class="showcase" aria-label="Our projects" aria-roledescription="carousel" data-showcase data-interval="5000">
        <div class="showcase__list" role="tablist" aria-label="Choose a project" hidden data-showcase-tabs>
            <?php foreach ($data['projects'] as $i => $project): ?>
                <button class="showcase__tab" type="button" role="tab" id="project-tab-<?= $i ?>"
                        aria-controls="project-<?= $i ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                        tabindex="<?= $i === 0 ? '0' : '-1' ?>" data-showcase-tab="<?= $i ?>">
                    <span class="showcase__tab-n"><?= sprintf('%02d', $i + 1) ?></span>
                    <span class="showcase__tab-label"><?= e($project['kicker']) ?></span>
                    <span class="showcase__tab-bar" aria-hidden="true"><span></span></span>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="showcase__stage">
            <?php foreach ($data['projects'] as $i => $project): ?>
                <article class="showcase__slide<?= $i === 0 ? ' is-active' : '' ?>" id="project-<?= $i ?>"
                         role="tabpanel" aria-labelledby="project-tab-<?= $i ?>" data-showcase-slide>
                    <figure class="showcase__media">
                        <?= picture_img($project['image'], $project['alt'], '(min-width: 960px) 46vw, 100vw') ?>
                        <span class="showcase__counter" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?> / <?= sprintf('%02d', $projectCount) ?></span>
                    </figure>
                    <div class="showcase__copy">
                        <p class="showcase__kicker"><?= e($project['kicker']) ?></p>
                        <h3 class="showcase__title"><?= e($project['title']) ?></h3>
                        <p class="showcase__text"><?= e($project['text']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>

            <div class="showcase__controls" hidden data-showcase-controls>
                <button class="showcase__btn" type="button" data-showcase-prev>
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="visually-hidden">Previous project</span>
                </button>
                <button class="showcase__btn" type="button" aria-pressed="false" data-showcase-pause>
                    <svg class="showcase__icon-pause" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M7 5h3v14H7zM14 5h3v14h-3z"/></svg>
                    <svg class="showcase__icon-play" aria-hidden="true" viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    <span class="visually-hidden">Pause slideshow</span>
                </button>
                <button class="showcase__btn" type="button" data-showcase-next>
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="visually-hidden">Next project</span>
                </button>
            </div>
        </div>
    </section>
</div>
