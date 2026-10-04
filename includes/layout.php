<?php
/**
 * Shared page shell: page_open() prints everything up to <main>,
 * page_close() closes it and adds the footer.
 *
 * Options for page_open():
 *   title        Page title (site name is appended)
 *   description  Meta description
 *   image        Social preview image path
 *   styles       Extra stylesheets (paths under the site root)
 *   scripts      Deferred scripts
 *   preload      Optional ['href' => ..., 'srcset' => ..., 'sizes' => ...] LCP image
 *   head         Extra raw HTML for <head> (already escaped/trusted)
 *   bodyStart    Callable run straight after <body> (e.g. the intro screen)
 */

declare(strict_types=1);

function page_open(array $page = []): void
{
    $title = isset($page['title']) ? $page['title'] . ' | ' . SITE_NAME : SITE_NAME;
    $description = $page['description'] ?? SITE_TAGLINE;
    $image = $page['image'] ?? 'assets/images/hero/hero-academics-1280.webp';
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="theme-color" content="#141414">
    <script>document.documentElement.classList.add('js');</script>
<?= $page['head'] ?? '' ?>

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:image" content="<?= e($image) ?>">

    <link rel="icon" href="<?= asset('assets/images/brand/favicon.webp') ?>" type="image/webp" sizes="256x256">
<?php if (!empty($page['preload'])): ?>

    <!-- Start the largest image downloading before CSS is parsed (LCP) -->
    <link rel="preload" as="image" type="image/webp" fetchpriority="high"
          href="<?= e($page['preload']['href']) ?>"
          imagesrcset="<?= e($page['preload']['srcset']) ?>"
          imagesizes="<?= e($page['preload']['sizes']) ?>">
<?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap">

    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
<?php foreach ($page['styles'] ?? [] as $style): ?>
    <link rel="stylesheet" href="<?= asset($style) ?>">
<?php endforeach; ?>
<?php foreach ($page['scripts'] ?? [] as $script): ?>
    <script src="<?= asset($script) ?>" defer></script>
<?php endforeach; ?>
</head>
<body>
<?php
    if (isset($page['bodyStart']) && is_callable($page['bodyStart'])) {
        $page['bodyStart']();
    }
    ?>
    <main id="main">
<?php
}

function page_close(): void
{
    ?>
    </main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
<?php
}
