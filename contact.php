<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/contact-page/handler.php';

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
session_start();

$schools = require __DIR__ . '/includes/data/schools.php';
$result = ['ok' => false, 'errors' => [], 'values' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = contact_handle($_POST, $schools);

    // contact.js posts with fetch and asks for JSON
    if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($result['ok'] ? 200 : 422);
        echo json_encode(['ok' => $result['ok'], 'errors' => $result['errors'], 'csrf' => contact_csrf_token()]);
        exit;
    }

    // Without JS: Post/Redirect/Get on success, re-render with errors otherwise
    if ($result['ok']) {
        $_SESSION['contact_sent'] = true;
        header('Location: contact.php#message', true, 303);
        exit;
    }
    http_response_code(422);
}

$sent = !empty($_SESSION['contact_sent']);
unset($_SESSION['contact_sent']);

$now = new DateTimeImmutable('now', new DateTimeZone(SITE_TIMEZONE));

page_open([
    'title'       => 'Contact Us',
    'description' => 'Call, email or visit the Anglican Diocese of Harare Education Department, or send us a message.',
    'styles'      => ['assets/css/contact.css'],
    'scripts'     => ['assets/js/header.js', 'assets/js/sections.js', 'assets/js/contact.js'],
    'bodyStart'   => static function (): void {
        require __DIR__ . '/includes/site-header.php';
    },
]);

require __DIR__ . '/includes/contact-page/hero.php';
require __DIR__ . '/includes/contact-page/methods.php';
require __DIR__ . '/includes/contact-page/form.php';
require __DIR__ . '/includes/contact-page/map.php';
require __DIR__ . '/includes/contact-page/faq.php';

page_close();
