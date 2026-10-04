<?php
/**
 * Directory: sticky toolbar (level filter + search), a sticky index that
 * tracks the school in view, and a full profile card per school.
 * Behaviour: assets/js/institutes.js. Without JS every profile is shown.
 *
 * @var array $schools
 */

declare(strict_types=1);

$levelCounts = array_count_values(array_column($schools, 'level'));

?>
<section class="directory" id="directory" aria-labelledby="directory-title">
    <div class="directory__inner">
        <header class="directory__header">
            <div>
                <p class="section-eyebrow reveal">Directory</p>
                <h2 class="directory__title split" id="directory-title"><?php foreach (['Meet', 'our', 'schools'] as $w => $word): ?><span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span> <?php endforeach; ?></h2>
            </div>
            <p class="directory__intro reveal">
                History, facts, results and photos for every school. Each may have its own admission criteria,
                so please confirm details with the school you are interested in.
            </p>
        </header>

        <div class="toolbar" hidden data-toolbar>
            <div class="seg" role="group" aria-label="Filter by level">
                <button type="button" aria-pressed="true" data-filter="all">All <span><?= count($schools) ?></span></button>
                <button type="button" aria-pressed="false" data-filter="secondary">Secondary <span><?= (int) ($levelCounts['secondary'] ?? 0) ?></span></button>
                <button type="button" aria-pressed="false" data-filter="primary">Primary <span><?= (int) ($levelCounts['primary'] ?? 0) ?></span></button>
            </div>
            <label class="toolbar__search">
                <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span class="visually-hidden">Search schools by name or place</span>
                <input type="search" placeholder="Search by name or place…" autocomplete="off" data-search>
            </label>
            <p class="toolbar__count" aria-live="polite" data-count><?= count($schools) ?> schools</p>
        </div>

        <div class="directory__layout">
            <nav class="dindex" aria-label="Schools" hidden data-index>
                <ol role="list">
                    <?php foreach ($schools as $i => $school): ?>
                        <li data-index-item="<?= e($school['id']) ?>">
                            <a href="#<?= e($school['id']) ?>">
                                <span class="dindex__n"><?= sprintf('%02d', $i + 1) ?></span>
                                <span class="dindex__name"><?= e($school['name']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>

            <div class="profiles">
                <?php foreach ($schools as $i => $school):
                    $gallery = $school['gallery'];
                    $tags = array_map('trim', explode('·', $school['tag'])); ?>
                    <article class="profile reveal" id="<?= e($school['id']) ?>" aria-labelledby="name-<?= e($school['id']) ?>"
                             data-level="<?= e($school['level']) ?>"
                             data-search="<?= e(strtolower($school['name'] . ' ' . $school['place'] . ' ' . $school['tag'])) ?>"
                             data-profile data-parallax data-spotlight>
                        <header class="profile__head">
                            <span class="profile__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                            <div class="profile__titles">
                                <ul class="profile__tags" role="list">
                                    <?php foreach ($tags as $tag): ?><li><?= e($tag) ?></li><?php endforeach; ?>
                                </ul>
                                <h3 class="profile__name" id="name-<?= e($school['id']) ?>"><?= e($school['name']) ?></h3>
                                <p class="profile__place">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                                    <?= e($school['place']) ?>
                                </p>
                            </div>
                            <?php if ($school['motto']): ?>
                                <p class="profile__motto"><span>Motto</span>“<?= e($school['motto']) ?>”</p>
                            <?php endif; ?>
                        </header>

                        <div class="profile__gallery<?= count($gallery) > 1 ? ' has-thumbs' : '' ?>" data-gallery>
                            <?php if ($gallery): ?>
                                <div class="profile__stage">
                                    <?php foreach ($gallery as $g => $photo): ?>
                                        <figure class="profile__photo<?= $g === 0 ? ' is-active' : '' ?>" data-photo>
                                            <?= picture_img($photo['image'], '', '(min-width: 1100px) 760px, 100vw') /* the figcaption describes it */ ?>
                                            <figcaption><?= e($photo['caption']) ?></figcaption>
                                        </figure>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($gallery) > 1): ?>
                                    <div class="profile__thumbs" role="group" aria-label="<?= e($school['name']) ?> photos">
                                        <?php foreach ($gallery as $g => $photo):
                                            $thumb = responsive_image($photo['image']); ?>
                                            <button type="button" class="profile__thumb<?= $g === 0 ? ' is-active' : '' ?>"
                                                    aria-pressed="<?= $g === 0 ? 'true' : 'false' ?>" data-thumb="<?= $g ?>">
                                                <img src="<?= e($thumb['src']) ?>" alt="" width="<?= $thumb['width'] ?>" height="<?= $thumb['height'] ?>" loading="lazy" decoding="async">
                                                <span class="visually-hidden">Show photo <?= $g + 1 ?>: <?= e($photo['caption']) ?></span>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="profile__monogram" aria-hidden="true">
                                    <span class="profile__monogram-letters"><?= e(school_initials($school['name'])) ?></span>
                                    <span class="profile__monogram-note">Photos coming soon</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="profile__body">
                            <div class="profile__story">
                                <p><?= e($school['text']) ?></p>
                                <?php if ($school['highlights']): ?>
                                    <ul class="profile__highlights" role="list">
                                        <?php foreach ($school['highlights'] as $h): ?><li><?= e($h) ?></li><?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php if ($school['website']): ?>
                                    <a class="profile__site" href="<?= e($school['website']) ?>" target="_blank" rel="noopener">
                                        Visit school website<span class="visually-hidden"> (opens in a new tab)</span>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" width="14" height="14"><path d="M7 17 17 7M9 7h8v8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!$school['facts'] && !$school['highlights']): ?>
                                    <p class="profile__pending">More details about this school are being gathered.</p>
                                <?php endif; ?>
                            </div>

                            <?php if ($school['facts'] || $school['results']): ?>
                                <div class="profile__data">
                                    <?php if ($school['facts']): ?>
                                        <dl class="profile__facts">
                                            <?php foreach ($school['facts'] as $label => $value): ?>
                                                <div><dt><?= e($label) ?></dt><dd><?= e($value) ?></dd></div>
                                            <?php endforeach; ?>
                                        </dl>
                                    <?php endif; ?>
                                    <?php if ($school['results']) render_results_chart($school); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
                <p class="profiles__empty" hidden data-empty>No schools match your search.</p>
            </div>
        </div>
    </div>
</section>
