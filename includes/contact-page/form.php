<?php
/**
 * Message form. Works as a normal POST (Post/Redirect/Get); contact.js
 * upgrades it to a fetch submit with inline validation and a success state.
 *
 * @var array $result   ['ok', 'errors', 'values'] from contact_handle()
 * @var bool  $sent     true right after a successful non-JS submit
 * @var array $schools
 */

declare(strict_types=1);

$v = $result['values'];
$err = $result['errors'];
$val = static function (string $k) use ($v): string { return e($v[$k] ?? ''); };
$selectedTopic = $v['topic'] ?? 'general';

/** aria attributes + message for a field with an error */
$fieldError = static function (string $key) use ($err): array {
    if (!isset($err[$key])) {
        return ['', ''];
    }

    return [' aria-invalid="true" aria-describedby="err-' . $key . '"',
            '<p class="field__error" id="err-' . $key . '">' . e($err[$key]) . '</p>'];
};
?>
<section class="message" id="message" aria-labelledby="message-title">
    <div class="message__inner">
        <div class="message__intro">
            <p class="section-eyebrow reveal">Send a message</p>
            <h2 class="message__title split" id="message-title">
                <?php foreach (['How', 'can', 'we', 'help?'] as $w => $word): ?><span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span> <?php endforeach; ?>
            </h2>
            <p class="message__lead reveal">Fill in the form and the Education Office will get back to you. Fields marked <span aria-hidden="true">*</span><span class="visually-hidden">with an asterisk</span> are required.</p>

            <ol class="next reveal" role="list" aria-label="What happens next">
                <li><span>01</span><div><strong>We receive your message</strong> and pass it to the right person in the office.</div></li>
                <li><span>02</span><div><strong>We reply by email or phone</strong>, usually within a few working days.</div></li>
                <li><span>03</span><div><strong>Looking for a school?</strong> You can also contact it directly: <a href="institutes.php">see every school</a>.</div></li>
            </ol>
        </div>

        <div class="message__card reveal" data-form-card>
            <form class="cform<?= $sent ? ' is-sent' : '' ?>" action="contact.php#message" method="post" novalidate data-contact-form>
                <input type="hidden" name="csrf" value="<?= e(contact_csrf_token()) ?>" data-csrf>
                <input type="hidden" name="ts" value="<?= time() ?>">
                <!-- Honeypot: people never see or fill this -->
                <div class="cform__hp" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <p class="cform__alert" role="alert" data-form-alert<?= isset($err['form']) ? '' : ' hidden' ?>><?= e($err['form'] ?? '') ?></p>

                <fieldset class="topics">
                    <legend class="topics__legend">What is it about? <span aria-hidden="true">*</span></legend>
                    <div class="topics__list">
                        <?php foreach (contact_topics() as $key => $label): ?>
                            <label class="topic">
                                <input type="radio" name="topic" value="<?= e($key) ?>"<?= $selectedTopic === $key ? ' checked' : '' ?> data-topic>
                                <span><?= e($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?= $fieldError('topic')[1] ?>
                </fieldset>

                <div class="cform__row">
                    <?php [$attr, $msg] = $fieldError('name'); ?>
                    <div class="field" data-field="name">
                        <input class="field__input" type="text" id="f-name" name="name" value="<?= $val('name') ?>" placeholder=" " autocomplete="name" required minlength="2" maxlength="100"<?= $attr ?>>
                        <label class="field__label" for="f-name">Your name <span aria-hidden="true">*</span></label>
                        <?= $msg ?>
                    </div>
                    <?php [$attr, $msg] = $fieldError('email'); ?>
                    <div class="field" data-field="email">
                        <input class="field__input" type="email" id="f-email" name="email" value="<?= $val('email') ?>" placeholder=" " autocomplete="email" required maxlength="254"<?= $attr ?>>
                        <label class="field__label" for="f-email">Email address <span aria-hidden="true">*</span></label>
                        <?= $msg ?>
                    </div>
                </div>

                <div class="cform__row">
                    <?php [$attr, $msg] = $fieldError('phone'); ?>
                    <div class="field" data-field="phone">
                        <input class="field__input" type="tel" id="f-phone" name="phone" value="<?= $val('phone') ?>" placeholder=" " autocomplete="tel" maxlength="30"<?= $attr ?>>
                        <label class="field__label" for="f-phone">Phone (optional)</label>
                        <?= $msg ?>
                    </div>
                    <div class="field field--select" data-field="school" data-school-field>
                        <select class="field__input" id="f-school" name="school">
                            <option value="">Not about a particular school</option>
                            <?php foreach ($schools as $school): ?>
                                <option value="<?= e($school['id']) ?>"<?= ($v['school'] ?? '') === $school['id'] ? ' selected' : '' ?>><?= e($school['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="field__label is-fixed" for="f-school">School (optional)</label>
                    </div>
                </div>

                <?php [$attr, $msg] = $fieldError('message'); ?>
                <div class="field field--area" data-field="message">
                    <div class="field__box">
                        <textarea class="field__input" id="f-message" name="message" rows="6" placeholder=" " required minlength="10" maxlength="2000"<?= $attr ?> data-counted><?= $val('message') ?></textarea>
                        <label class="field__label" for="f-message">Your message <span aria-hidden="true">*</span></label>
                        <span class="field__count" aria-hidden="true"><span data-count>0</span> / 2000</span>
                    </div>
                    <?= $msg ?>
                </div>

                <div class="cform__foot">
                    <p class="cform__privacy">We only use your details to reply to you.</p>
                    <button class="cform__submit" type="submit" data-submit>
                        <span class="cform__submit-text">Send message</span>
                        <span class="cform__submit-icon" aria-hidden="true">
                            <svg class="cform__arrow" viewBox="0 0 24 24" width="18" height="18"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="cform__spinner"></span>
                        </span>
                    </button>
                </div>
            </form>

            <div class="sent" role="status" tabindex="-1" data-sent<?= $sent ? '' : ' hidden' ?>>
                <svg class="sent__check" viewBox="0 0 80 80" aria-hidden="true">
                    <circle cx="40" cy="40" r="36" pathLength="1"/>
                    <path d="m25 41 10 10 20-22" pathLength="1"/>
                </svg>
                <h3 class="sent__title">Message sent. Thank you!</h3>
                <p class="sent__text">The Education Office has your message and will get back to you soon. For anything urgent, call <a href="tel:<?= e(SITE_PHONE) ?>"><?= e(SITE_PHONE_DISPLAY) ?></a>.</p>
                <button class="sent__again" type="button" data-send-another hidden>Send another message</button>
            </div>
        </div>
    </div>
</section>
