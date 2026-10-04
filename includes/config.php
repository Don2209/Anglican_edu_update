<?php
/**
 * Site-wide settings and helpers.
 */

declare(strict_types=1);

const SITE_NAME    = 'Anglican Diocese of Harare Education';
const SITE_TAGLINE = 'Faith, excellence and holistic education across the Anglican schools of the Diocese of Harare.';
const SITE_PHONE   = '+263773525089';
const SITE_PHONE_DISPLAY = '+263 773 525 089';
const SITE_ADDRESS = ['87 Kwame Nkrumah Avenue', 'Harare, Zimbabwe'];
const SITE_ADDRESS_DETAIL = 'Office Number 2, First Floor';
const SITE_EMAIL = 'info@anglicandioceseofharareedu.org.zw';
const SITE_TIMEZONE = 'Africa/Harare';

/**
 * Contact form delivery. Messages are always saved to storage/messages/.
 * Set to true once the server can send mail (sendmail/SMTP configured) to
 * also email each message to SITE_EMAIL.
 */
const CONTACT_MAIL_ENABLED = false;

/** Credit shown in the footer. */
const SITE_CREDIT_OWNER = 'Visyn Technologies Pvt Ltd';
const SITE_CREDIT_URL   = 'https://visyntech.co.zw';

/** Primary navigation (label => href). */
const NAV_LINKS = [
    'Home'          => 'index.php',
    'About'         => 'about.php',
    'Institutes'    => 'institutes.php',
    'Academics'     => 'about.php#academics',
    'Sports'        => 'about.php#sports',
    'Projects'      => 'about.php#projects',
    'News & Events' => 'news.php',
    'Gallery'       => 'gallery.php',
    'Contact'       => 'contact.php',
];

/**
 * Social profiles. Links with an empty URL are not rendered.
 * TODO: fill in the diocese's real profile URLs.
 */
const SOCIAL_LINKS = [
    'facebook'  => ['label' => 'Facebook',  'url' => ''],
    'instagram' => ['label' => 'Instagram', 'url' => ''],
    'x'         => ['label' => 'X',         'url' => ''],
];

/** SVG paths (24x24 viewBox, fill=currentColor) for each social network. */
const SOCIAL_ICONS = [
    'facebook'  => '<path d="M14 8h3V4h-3c-2.8 0-5 2.2-5 5v2H7v4h2v9h4v-9h3l1-4h-4V9c0-.6.4-1 1-1z"/>',
    'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2"/>',
    'x'         => '<path d="M17.8 3h3.1l-6.8 7.7L22 21h-6.2l-4.9-6.4L5.3 21H2.2l7.2-8.3L1.8 3h6.4l4.4 5.8L17.8 3zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5z"/>',
];

/** Escape a value for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Path to a static asset with a cache-busting version, so the files can be
 * cached long-term (see .htaccess) and still update right after a deploy.
 */
function asset(string $path): string
{
    $file = dirname(__DIR__) . '/' . ltrim($path, '/');
    $version = is_file($file) ? (string) filemtime($file) : '1';

    return e($path) . '?v=' . $version;
}

/** Name of the page currently being served, e.g. "index.php". */
function current_page(): string
{
    $page = basename((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));

    // A directory URL such as "/AnglicanEdu/" serves index.php.
    return str_ends_with($page, '.php') ? $page : 'index.php';
}

/**
 * Responsive image attributes for a WebP set saved as "{$base}-{width}.webp"
 * (e.g. assets/images/about-page/track-640.webp). Uses the real pixel widths on
 * disk, so small sources that were never upscaled are described correctly.
 *
 * @return array{src: string, srcset: string, width: int, height: int}
 */
function responsive_image(string $base): array
{
    static $cache = [];
    if (isset($cache[$base])) {
        return $cache[$base];
    }

    $root = dirname(__DIR__) . '/';
    $sources = [];
    foreach ([640, 768, 1280, 1600, 1920] as $size) {
        $path = "{$base}-{$size}.webp";
        $info = is_file($root . $path) ? getimagesize($root . $path) : false;
        if ($info) {
            $sources[$info[0]] = ['path' => $path, 'h' => $info[1]];
        }
    }
    ksort($sources);

    if (!$sources) {
        return $cache[$base] = ['src' => "{$base}.webp", 'srcset' => '', 'width' => 800, 'height' => 600];
    }

    $first = reset($sources);
    $srcset = [];
    foreach ($sources as $w => $s) {
        $srcset[] = "{$s['path']} {$w}w";
    }

    return $cache[$base] = [
        'src'    => $first['path'],
        'srcset' => implode(', ', $srcset),
        'width'  => (int) array_key_first($sources),
        'height' => (int) $first['h'],
    ];
}

/** <img> tag for a responsive_image() set. $attrs are added as-is after escaping. */
function picture_img(string $base, string $alt, string $sizes, array $attrs = []): string
{
    $img = responsive_image($base);
    $html = '<img src="' . e($img['src']) . '"';
    if ($img['srcset'] !== '') {
        $html .= ' srcset="' . e($img['srcset']) . '" sizes="' . e($sizes) . '"';
    }
    $html .= ' width="' . $img['width'] . '" height="' . $img['height'] . '" alt="' . e($alt) . '"';
    $attrs += ['loading' => 'lazy', 'decoding' => 'async'];
    foreach ($attrs as $name => $value) {
        $html .= ' ' . e((string) $name) . '="' . e((string) $value) . '"';
    }

    return $html . '>';
}

/** Two-letter monogram for a school, e.g. "St Clare's Primary School" → "SC". */
function school_initials(string $name): string
{
    $skip = ['School', 'High', 'Primary', 'Girls'];
    $letters = '';
    foreach (preg_split('/[\s,]+/', preg_replace("/'s\\b/", '', $name)) as $word) {
        if ($word !== '' && ctype_upper($word[0]) && !in_array($word, $skip, true)) {
            $letters .= $word[0];
        }
        if (strlen($letters) === 2) {
            break;
        }
    }

    return $letters;
}
