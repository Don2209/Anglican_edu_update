<?php
/**
 * "At a glance": one table comparing every school. Columns with numbers can
 * be sorted (assets/js/institutes.js); learner counts get an inline bar.
 *
 * @var array $schools
 * @var callable $factNumber
 */

declare(strict_types=1);

$maxLearners = max(array_filter(array_map(static fn ($s) => $factNumber($s, 'Learners'), $schools)) ?: [1]);
?>
<section class="glance" id="glance" aria-labelledby="glance-title">
    <div class="glance__inner">
        <header class="glance__header">
            <p class="section-eyebrow reveal">At a glance</p>
            <h2 class="glance__title split" id="glance-title"><?php foreach (['Compare', 'our', 'schools'] as $w => $word): ?><span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span> <?php endforeach; ?></h2>
        </header>

        <div class="glance__scroll reveal" data-glance tabindex="0" role="region" aria-labelledby="glance-title">
            <table class="glance__table" data-sortable>
                <thead>
                    <tr>
                        <th scope="col">School</th>
                        <th scope="col">Level</th>
                        <th scope="col" aria-sort="none"><button type="button" data-sort="founded">Founded <span aria-hidden="true"></span></button></th>
                        <th scope="col">Location</th>
                        <th scope="col" aria-sort="none"><button type="button" data-sort="learners">Learners <span aria-hidden="true"></span></button></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schools as $r => $school):
                        $year = $factNumber($school, 'Founded');
                        $count = $factNumber($school, 'Learners'); ?>
                        <tr style="--r: <?= $r ?>" data-founded="<?= $year ?? '' ?>" data-learners="<?= $count ?? '' ?>">
                            <th scope="row"><a href="#<?= e($school['id']) ?>"><?= e($school['name']) ?></a></th>
                            <td><span class="glance__level glance__level--<?= e($school['level']) ?>"><?= e(ucfirst($school['level'])) ?></span></td>
                            <td><?= $year ?? '—' ?></td>
                            <td><?= e($school['place']) ?></td>
                            <td>
                                <?php if ($count): ?>
                                    <span class="glance__bar" style="--w: <?= round($count / $maxLearners * 100, 1) ?>"><span><?= number_format($count) ?></span></span>
                                <?php else: ?>
                                    <span class="glance__na">Not published</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
