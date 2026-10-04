<?php
/**
 * Chapter 04: Projects, told as a scroll story: the photo stays pinned while
 * each project's text scrolls past, swapping the image with a wipe.
 * On small screens each project shows its own photo inline instead.
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

    <div class="story" data-story>
        <div class="story__media" aria-hidden="true">
            <div class="story__frame">
                <?php foreach ($data['projects'] as $i => $project): ?>
                    <div class="story__layer<?= $i === 0 ? ' is-active' : '' ?>" data-story-layer>
                        <?= picture_img($project['image'], '', '(min-width: 900px) 46vw, 100vw') ?>
                    </div>
                <?php endforeach; ?>
                <span class="story__counter"><span data-story-current>01</span> / <?= sprintf('%02d', $projectCount) ?></span>
            </div>
            <div class="story__dots">
                <?php for ($i = 0; $i < $projectCount; $i++): ?><span<?= $i === 0 ? ' class="is-active"' : '' ?> data-story-dot></span><?php endfor; ?>
            </div>
        </div>

        <ol class="story__steps" role="list">
            <?php foreach ($data['projects'] as $i => $project): ?>
                <li class="story__step<?= $i === 0 ? ' is-active' : '' ?>" data-story-step="<?= $i ?>">
                    <figure class="story__inline">
                        <?= picture_img($project['image'], $project['alt'], '100vw') ?>
                    </figure>
                    <p class="story__kicker"><span><?= sprintf('%02d', $i + 1) ?></span> <?= e($project['kicker']) ?></p>
                    <h3 class="story__title"><?= e($project['title']) ?></h3>
                    <p class="story__text"><?= e($project['text']) ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
