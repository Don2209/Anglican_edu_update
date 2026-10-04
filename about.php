<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/partials/results-chart.php';

$data = require __DIR__ . '/includes/about-page/data.php';

page_open([
    'title'       => 'About Us',
    'description' => 'Institutions, academics, sports and culture, and projects across the Anglican schools of the Diocese of Harare.',
    'image'       => 'assets/images/about-page/institutions-1280.webp',
    'styles'      => ['assets/css/charts.css', 'assets/css/about.css'],
    'scripts'     => ['assets/js/header.js', 'assets/js/sections.js', 'assets/js/about.js'],
    'bodyStart'   => static function (): void {
        require __DIR__ . '/includes/site-header.php';
    },
]);

require __DIR__ . '/includes/about-page/hero.php';
require __DIR__ . '/includes/about-page/chapters.php';

page_close();
