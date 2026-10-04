<?php
/**
 * Two crossing ribbons of school names that slide in opposite directions as
 * the page scrolls (--p from assets/js/sections.js). Decorative.
 *
 * @var array $schools
 */

declare(strict_types=1);

$names = array_map(static fn ($s) => preg_replace('/,.*$/', '', $s['name']), $schools);
?>
<div class="iband" aria-hidden="true" data-parallax>
    <div class="iband__ribbon iband__ribbon--a">
        <div class="iband__track">
            <?php for ($n = 0; $n < 3; $n++): foreach ($names as $name): ?>
                <span><?= e($name) ?></span><i>✦</i>
            <?php endforeach; endfor; ?>
        </div>
    </div>
    <div class="iband__ribbon iband__ribbon--b">
        <div class="iband__track">
            <?php for ($n = 0; $n < 3; $n++): foreach (array_reverse($names) as $name): ?>
                <span><?= e($name) ?></span><i>✦</i>
            <?php endforeach; endfor; ?>
        </div>
    </div>
</div>
