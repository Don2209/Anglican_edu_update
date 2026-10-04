<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/partials/results-chart.php';

$schools = require __DIR__ . '/includes/data/schools.php';

/** First whole number in a fact such as "1,268 (605 boys …)" or "60+". */
$factNumber = static function (array $school, string $key): ?int {
    $value = $school['facts'][$key] ?? null;
    if ($value === null || !preg_match('/\d[\d,]*/', $value, $m)) {
        return null;
    }

    return (int) str_replace(',', '', $m[0]);
};

$learners = array_filter(array_map(static fn ($s) => $factNumber($s, 'Learners'), $schools));
$teachers = array_filter(array_map(static fn ($s) => $factNumber($s, 'Staff'), $schools));
$founded  = array_filter(array_map(static fn ($s) => $factNumber($s, 'Founded'), $schools));

$stats = [
    'schools'  => count($schools),
    'learners' => array_sum($learners),
    'teachers' => array_sum($teachers),
    'oldest'   => $founded ? min($founded) : null,
];

page_open([
    'title'       => 'Our Institutions',
    'description' => 'Every school in the Anglican Diocese of Harare education family: history, facts, results and photos.',
    'image'       => 'assets/images/institutes/st-marks-bus-1280.webp',
    'styles'      => ['assets/css/charts.css', 'assets/css/institutes.css'],
    'scripts'     => ['assets/js/header.js', 'assets/js/sections.js', 'assets/js/institutes.js'],
    'bodyStart'   => static function (): void {
        require __DIR__ . '/includes/site-header.php';
    },
]);

require __DIR__ . '/includes/institutes-page/hero.php';
require __DIR__ . '/includes/institutes-page/band.php';
require __DIR__ . '/includes/institutes-page/directory.php';
require __DIR__ . '/includes/institutes-page/glance.php';
require __DIR__ . '/includes/institutes-page/cta.php';

page_close();
