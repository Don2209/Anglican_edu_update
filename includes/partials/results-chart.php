<?php
/**
 * Pass-rate bar chart for a school (O vs A Level, or Grade 7 for primary).
 * Styles: assets/css/charts.css. Series colours were checked with the
 * dataviz validator (blue #2d6cb5 / crest red, light and dark).
 */

declare(strict_types=1);

function render_results_chart(array $school): void
{
    $isPrimary = $school['level'] === 'primary';
    $series = $isPrimary ? ['Grade 7'] : ['O Level', 'A Level'];
    $lastYear = array_key_last($school['results']);
    ?>
    <figure class="results">
        <figcaption class="results__head">
            <span class="results__title"><?= $isPrimary ? 'Grade 7 pass rate' : 'Pass rates' ?></span>
            <?php if (!$isPrimary): ?>
                <span class="results__legend" aria-hidden="true">
                    <span class="results__key results__key--o">O Level</span>
                    <span class="results__key results__key--a">A Level</span>
                </span>
            <?php endif; ?>
        </figcaption>

        <div class="results__plot<?= $isPrimary ? ' results__plot--single' : '' ?>" aria-hidden="true">
            <span class="results__grid" style="--at: 100">100%</span>
            <span class="results__grid" style="--at: 50">50%</span>
            <?php foreach ($school['results'] as $year => $values): ?>
                <div class="results__year">
                    <div class="results__bars">
                        <?php foreach ($values as $k => $v): if ($v === null) continue;
                            $cls = $isPrimary ? 'a' : ($k === 0 ? 'o' : 'a'); ?>
                            <span class="results__bar results__bar--<?= $cls ?>" style="--v: <?= (float) $v ?>"
                                  data-tip="<?= e($year . ' · ' . $series[$k] . ': ' . rtrim(rtrim(number_format((float) $v, 2), '0'), '.') . '%') ?>">
                                <?php if ($year === $lastYear): ?><span class="results__value"><?= e(rtrim(rtrim(number_format((float) $v, 1), '0'), '.')) ?>%</span><?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <span class="results__label"><?= e((string) $year) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <details class="results__table">
            <summary>View as table</summary>
            <table>
                <thead><tr><th scope="col">Year</th><?php foreach ($series as $name): ?><th scope="col"><?= e($name) ?></th><?php endforeach; ?></tr></thead>
                <tbody>
                    <?php foreach ($school['results'] as $year => $values): ?>
                        <tr><th scope="row"><?= e((string) $year) ?></th><?php foreach ($values as $v): ?><td><?= $v === null ? '—' : e(rtrim(rtrim(number_format((float) $v, 2), '0'), '.')) . '%' ?></td><?php endforeach; ?></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </details>
    </figure>
<?php
}
