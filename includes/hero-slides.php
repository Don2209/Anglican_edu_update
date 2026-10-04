<?php
/**
 * Hero slider content. Each slide expects these files in assets/images/hero/:
 *   hero-{image}-{768|1280|1920}.webp  (background, responsive)
 *   hero-{image}-thumb.webp            (192px square thumbnail)
 */

declare(strict_types=1);

return [
    [
        'image'   => 'academics',
        'alt'     => 'Students in maroon blazers working through a maths lesson with calculators',
        'title'   => 'Academic Excellence',
        'text'    => 'The roots of education are bitter, but the fruit is sweet. Our schools pair strong teaching with the discipline to see every learner through.',
        'cta'     => ['label' => 'Explore academics', 'href' => 'about.php#academics'],
    ],
    [
        'image'   => 'sports',
        'alt'     => 'An athlete clearing the high-jump bar at an inter-school sports day',
        'title'   => 'Sport & Athletics',
        'text'    => 'From district meets to national stadiums, our learners compete with heart, building character, teamwork and resilience.',
        'cta'     => ['label' => 'See our sports', 'href' => 'about.php#sports'],
    ],
    [
        'image'   => 'skills',
        'alt'     => 'Learners in overalls welding steel bed frames in a school workshop',
        'title'   => 'Practical Skills',
        'text'    => 'The great aim of education is not knowledge, but action. Hands-on technical training prepares learners for work and enterprise.',
        'cta'     => ['label' => 'View our projects', 'href' => 'about.php#projects'],
    ],
    [
        'image'   => 'culture',
        'alt'     => 'Three students playing marimbas together in a school music room',
        'title'   => 'Arts & Culture',
        'text'    => 'Music, heritage and Christian values sit at the heart of a holistic education that shapes well-rounded young people.',
        'cta'     => ['label' => 'About the diocese', 'href' => 'about.php'],
    ],
];
