<?php
/**
 * Chapter 01: Institutions. A big-type index of schools (filterable, each row
 * expands) and the admissions process, which travels sideways as you scroll.
 *
 * @var array $data
 */

declare(strict_types=1);

$levelCounts = array_count_values(array_column($data['schools'], 'level'));

// One icon per admissions step: form, review, interview, acceptance, waitlist
$admissionIcons = [
    '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h4"/>',
    '<circle cx="11" cy="11" r="6"/><path d="m20 20-4.5-4.5M9 11l1.5 1.5L13.5 9.5"/>',
    '<path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"/><path d="M8.5 11h.01M12 11h.01M15.5 11h.01"/>',
    '<circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16.5 9.5"/>',
    '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
];
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
                        <div class="schools__story">
                            <p><?= e($school['text']) ?></p>
                            <?php if ($school['highlights']): ?>
                                <ul class="schools__highlights" role="list">
                                    <?php foreach ($school['highlights'] as $h): ?><li><?= e($h) ?></li><?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if ($school['motto'] || $school['website']): ?>
                                <div class="schools__meta">
                                    <?php if ($school['motto']): ?>
                                        <p class="schools__motto"><span>Motto</span> “<?= e($school['motto']) ?>”</p>
                                    <?php endif; ?>
                                    <?php if ($school['website']): ?>
                                        <a class="schools__site" href="<?= e($school['website']) ?>" target="_blank" rel="noopener">
                                            Visit school website<span class="visually-hidden"> (opens in a new tab)</span>
                                            <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M7 17 17 7M9 7h8v8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($school['facts'] || $school['results']): ?>
                            <div class="schools__data">
                                <?php if ($school['facts']): ?>
                                    <dl class="schools__facts">
                                        <?php foreach ($school['facts'] as $label => $value): ?>
                                            <div><dt><?= e($label) ?></dt><dd><?= e($value) ?></dd></div>
                                        <?php endforeach; ?>
                                    </dl>
                                <?php endif; ?>

                                <?php if ($school['results']) render_results_chart($school); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </details>
            </li>
        <?php endforeach; ?>
    </ol>

    <a class="schools__all" href="institutes.php">
        View every school in full
        <span><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    </a>
</div>

<section class="process" id="admissions" aria-labelledby="admissions-title" style="--steps: <?= count($data['admissions']) ?>" data-process>
    <div class="process__sticky">
        <header class="process__header">
            <div>
                <p class="section-eyebrow">Admissions</p>
                <h3 class="process__title" id="admissions-title">How to join an Anglican school</h3>
            </div>
            <div class="process__aside">
                <p class="process__note">Each school may have its own criteria, deadlines and procedures, so please confirm the details with the school you are interested in.</p>
                <p class="process__readout" aria-hidden="true" hidden data-process-readout>
                    Step <span data-process-current>01</span> <span class="process__readout-of">of <?= sprintf('%02d', count($data['admissions'])) ?></span>
                </p>
            </div>
        </header>

        <div class="process__viewport">
            <div class="process__lane" data-process-track>
                <span class="process__rail" aria-hidden="true"><span data-process-fill></span></span>
                <ol class="process__track" role="list">
                <?php foreach ($data['admissions'] as $i => $step): ?>
                    <li class="process__step<?= $i === 0 ? ' is-active' : '' ?>" data-process-step>
                        <span class="process__node" aria-hidden="true"><span></span></span>
                        <article class="process__card">
                            <span class="process__ghost" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                            <div class="process__card-top">
                                <span class="process__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?= $admissionIcons[$i] ?? '' ?></svg>
                                </span>
                                <span class="process__n">Step <?= sprintf('%02d', $i + 1) ?></span>
                            </div>
                            <h4 class="process__step-title"><?= e($step['title']) ?></h4>
                            <p><?= e($step['text']) ?></p>
                        </article>
                    </li>
                <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</section>
