<?php
/**
 * Contact form: validation, spam protection and delivery.
 *
 * Protection: CSRF token (session), honeypot field, minimum fill time and a
 * per-session rate limit. Every valid message is appended to
 * storage/messages/YYYY-MM.jsonl (blocked from the web); it is also emailed to
 * SITE_EMAIL when CONTACT_MAIL_ENABLED is true.
 */

declare(strict_types=1);

const CONTACT_MIN_SECONDS = 3;     // faster than this is a bot
const CONTACT_COOLDOWN    = 60;    // seconds between messages per session

function contact_topics(): array
{
    return [
        'general'      => 'General enquiry',
        'admissions'   => 'Admissions',
        'schools'      => 'A specific school',
        'partnerships' => 'Partnerships & sponsorship',
        'media'        => 'News & media',
    ];
}

function contact_csrf_token(): string
{
    if (empty($_SESSION['contact_csrf'])) {
        $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['contact_csrf'];
}

/**
 * @param array $schools  from includes/data/schools.php (for the school select)
 * @return array{ok: bool, errors: array<string,string>, values: array<string,string>}
 */
function contact_handle(array $post, array $schools): array
{
    $field = static function (string $key, int $max) use ($post): string {
        return mb_substr(trim((string) ($post[$key] ?? '')), 0, $max);
    };

    $values = [
        'name'    => $field('name', 100),
        'email'   => $field('email', 254),
        'phone'   => $field('phone', 30),
        'topic'   => $field('topic', 30),
        'school'  => $field('school', 60),
        'message' => $field('message', 2000),
    ];

    $expected = (string) ($_SESSION['contact_csrf'] ?? '');
    if ($expected === '' || !hash_equals($expected, (string) ($post['csrf'] ?? ''))) {
        return ['ok' => false, 'errors' => ['form' => 'Your session expired. Please send the form again.'], 'values' => $values];
    }

    // Bots: filled the hidden field or submitted impossibly fast. Pretend it worked.
    if (($post['website'] ?? '') !== '' || time() - (int) ($post['ts'] ?? 0) < CONTACT_MIN_SECONDS) {
        return ['ok' => true, 'errors' => [], 'values' => []];
    }

    if (isset($_SESSION['contact_last']) && time() - $_SESSION['contact_last'] < CONTACT_COOLDOWN) {
        return ['ok' => false, 'errors' => ['form' => 'Thanks, we already have your message. Please wait a minute before sending another.'], 'values' => $values];
    }

    $errors = [];
    if (mb_strlen($values['name']) < 2) {
        $errors['name'] = 'Please tell us your name.';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address, like name@example.com.';
    }
    if ($values['phone'] !== '' && !preg_match('/^\+?[0-9 ()-]{7,20}$/', $values['phone'])) {
        $errors['phone'] = 'Please enter a valid phone number, or leave it blank.';
    }
    if (!array_key_exists($values['topic'], contact_topics())) {
        $errors['topic'] = 'Please choose what your message is about.';
    }
    $schoolIds = array_column($schools, 'id');
    if ($values['school'] !== '' && !in_array($values['school'], $schoolIds, true)) {
        $values['school'] = '';
    }
    if (mb_strlen($values['message']) < 10) {
        $errors['message'] = 'Please write a little more (at least 10 characters).';
    }

    if ($errors) {
        return ['ok' => false, 'errors' => $errors, 'values' => $values];
    }

    if (!contact_store($values)) {
        return ['ok' => false, 'errors' => ['form' => 'Sorry, we could not send your message just now. Please call or email us instead.'], 'values' => $values];
    }
    if (CONTACT_MAIL_ENABLED) {
        contact_mail($values, $schools);
    }

    $_SESSION['contact_last'] = time();
    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));   // fresh token for the next message

    return ['ok' => true, 'errors' => [], 'values' => []];
}

function contact_store(array $values): bool
{
    $dir = dirname(__DIR__, 2) . '/storage/messages';
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
        return false;
    }

    $record = ['received' => date(DATE_ATOM), 'ip' => $_SERVER['REMOTE_ADDR'] ?? ''] + $values;
    $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

    return @file_put_contents($dir . '/' . date('Y-m') . '.jsonl', $line, FILE_APPEND | LOCK_EX) !== false;
}

function contact_mail(array $values, array $schools): void
{
    $topic = contact_topics()[$values['topic']];
    $schoolName = '';
    foreach ($schools as $school) {
        if ($school['id'] === $values['school']) {
            $schoolName = $school['name'];
        }
    }

    // Header values are single-line: strip CR/LF to prevent header injection
    $clean = static function (string $v): string {
        return str_replace(["\r", "\n"], ' ', $v);
    };

    $body = "Name: {$values['name']}\nEmail: {$values['email']}\nPhone: {$values['phone']}\nTopic: {$topic}\n"
          . ($schoolName ? "School: {$schoolName}\n" : '') . "\n{$values['message']}\n";

    @mail(
        SITE_EMAIL,
        $clean('Website enquiry: ' . $topic),
        $body,
        'Reply-To: ' . $clean($values['email']) . "\r\nContent-Type: text/plain; charset=UTF-8"
    );
}
