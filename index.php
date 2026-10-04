<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/layout.php';

$preloaderCss = (string) file_get_contents(__DIR__ . '/assets/css/preloader.css');

page_open([
    // Intro screen: skip it if already seen this session, and inline its CSS so it paints first
    'head' => "    <script>try { if (sessionStorage.getItem('ade-intro')) document.documentElement.classList.add('intro-seen'); } catch (e) {}</script>\n"
            . "    <style>{$preloaderCss}</style>\n",
    'preload' => [
        'href'   => 'assets/images/hero/hero-academics-1280.webp',
        'srcset' => 'assets/images/hero/hero-academics-768.webp 768w, assets/images/hero/hero-academics-1280.webp 1280w, assets/images/hero/hero-academics-1920.webp 1920w',
        'sizes'  => '100vw',
    ],
    'scripts' => ['assets/js/hero.js', 'assets/js/sections.js', 'assets/js/discover.js', 'assets/js/gallery.js'],
    'bodyStart' => static function (): void {
        require __DIR__ . '/includes/preloader.php';
    },
]);

require __DIR__ . '/includes/hero.php';
require __DIR__ . '/includes/about.php';
require __DIR__ . '/includes/marquee.php';
require __DIR__ . '/includes/discover.php';
require __DIR__ . '/includes/academics.php';
require __DIR__ . '/includes/gallery.php';

page_close();
