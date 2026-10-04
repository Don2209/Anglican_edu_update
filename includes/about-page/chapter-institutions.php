<?php
/**
 * Chapter 01: Institutions. A big-type index of schools (filterable, each row
 * expands) and the admissions process, which travels sideways as you scroll.
 *
 * @var array $data
 */

declare(strict_types=1);

$levelCounts = array_count_values(array_column($data['schools'], 'level'));
?>
<div class="chapter__body">
    <div class="chapter__intro reveal">
        <p class="chapter__lead">
            A diverse family of schools, from primary to secondary, committed to quality education, Christian
            values, inclusivity and holistic development.
        </p>
        <div class="seg" role="group" aria-label="Filter schools" data-school-filter hidden>
            <button type="button" aria-pressed="true" data-level="all">All <span><?= count($data['schools']) ?></span></button>
            <button type="button" aria-pressed="false" data-level="secondary">Secondary <span><?= (int) ($levelCounts['secondary'] ?? 0) ?></span></button>
            <button type="button" aria-pressed="false" data-level="primary">Primary <span><?= (int) ($levelCounts['primary'] ?? 0) ?></span></button>
        </div>
    </div>

    <ol class="schools" role="list" data-schools>
        <?php foreach ($data['schools'] as $i => $school): ?>
            <li class="schools__item reveal" id="<?= e($school['id']) ?>" data-level="<?= e($school['level']) ?>" style="--delay: <?= $i * 50 ?>ms">
                <details class="schools__details" name="schools">
                    <summary class="schools__row">
                        <span class="schools__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                        <span class="schools__name"><?= e($school['name']) ?></span>
                        <span class="schools__place"><?= e($school['place']) ?></span>
                        <span class="schools__tag"><?= e($school['tag']) ?></span>
                        <span class="schools__plus" aria-hidden="true"></span>
                    </summary>
                    <div class="schools__panel">
                        <p><?= e($school['text']) ?></p>
                    </div>
                </details>
            </li>
        <?php endforeach; ?>
    </ol>
</div>

<section class="process" id="admissions" aria-labelledby="admissions-title" style="--steps: <?= count($data['admissions']) ?>" data-process>
    <div class="process__sticky">
        <header class="process__header">
            <p class="section-eyebrow">Admissions</p>
            <h3 class="process__title" id="admissions-title">How to join an Anglican school</h3>
            <p class="process__note">Each school may have its own criteria, deadlines and procedures, so please confirm the details with the school you are interested in.</p>
        </header>
        <div class="process__viewport">
            <ol class="process__track" role="list" data-process-track>
                <?php foreach ($data['admissions'] as $i => $step): ?>
                    <li class="process__step">
                        <span class="process__n"><?= sprintf('%02d', $i + 1) ?></span>
                        <h4 class="process__step-title"><?= e($step['title']) ?></h4>
                        <p><?= e($step['text']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class="process__rail" aria-hidden="true"><span data-process-fill></span></div>
    </div>
</section>
