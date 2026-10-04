/**
 * Contact page: live Harare clock, copy-to-clipboard, the message form
 * (inline validation + fetch submit with a success state), and the
 * click-to-load map. The form works as a normal POST without this script.
 */
(function () {
    'use strict';

    /* --- Live clock in Harare -------------------------------------------- */

    var clock = document.querySelector('[data-clock]');
    var clockDay = document.querySelector('[data-clock-day]');
    if (clock && window.Intl) {
        var timeFmt = new Intl.DateTimeFormat('en-GB', { timeZone: 'Africa/Harare', hour: '2-digit', minute: '2-digit', hour12: false });
        var dayFmt = new Intl.DateTimeFormat('en-GB', { timeZone: 'Africa/Harare', weekday: 'long' });
        var renderClock = function () {
            var now = new Date();
            var parts = timeFmt.format(now).split(':');
            clock.innerHTML = parts[0] + '<span class="colon">:</span>' + parts[1];
            clockDay.textContent = dayFmt.format(now);
        };
        renderClock();
        window.setInterval(renderClock, 15000);
    }

    /* --- Copy buttons ---------------------------------------------------- */

    var toast = document.querySelector('[data-toast]');
    var toastTimer;
    function showToast(text) {
        toast.textContent = text;
        toast.classList.add('is-shown');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(function () { toast.classList.remove('is-shown'); }, 2200);
    }

    if (navigator.clipboard && window.isSecureContext) {
        document.querySelectorAll('[data-copy]').forEach(function (button) {
            button.hidden = false;
            var label = button.textContent;
            button.addEventListener('click', function () {
                navigator.clipboard.writeText(button.getAttribute('data-copy')).then(function () {
                    button.classList.add('is-copied');
                    button.textContent = 'Copied ✓';
                    showToast('Copied to clipboard');
                    window.setTimeout(function () {
                        button.classList.remove('is-copied');
                        button.textContent = label;
                    }, 2000);
                });
            });
        });
    }

    /* --- Message form ---------------------------------------------------- */

    var form = document.querySelector('[data-contact-form]');
    if (form) {
        var sent = document.querySelector('[data-sent]');
        var alertBox = form.querySelector('[data-form-alert]');
        var submit = form.querySelector('[data-submit]');
        var schoolField = form.querySelector('[data-school-field]');
        var counted = form.querySelector('[data-counted]');
        var countEl = form.querySelector('[data-count]');
        var again = document.querySelector('[data-send-another]');

        var messages = {
            name: 'Please tell us your name.',
            email: 'Please enter a valid email address, like name@example.com.',
            phone: 'Please enter a valid phone number, or leave it blank.',
            message: 'Please write a little more (at least 10 characters).'
        };

        // Show the school picker only for school-related topics
        function syncSchool() {
            var topic = form.querySelector('[data-topic]:checked');
            var relevant = topic && (topic.value === 'schools' || topic.value === 'admissions');
            var hasValue = schoolField.querySelector('select').value !== '';
            schoolField.classList.toggle('is-collapsed', !relevant && !hasValue);
        }
        form.querySelectorAll('[data-topic]').forEach(function (r) { r.addEventListener('change', syncSchool); });
        syncSchool();

        // Character counter
        function syncCount() {
            countEl.textContent = counted.value.length;
            countEl.parentElement.classList.toggle('is-near', counted.value.length > 1800);
        }
        counted.addEventListener('input', syncCount);
        syncCount();

        function setError(name, text) {
            var field = form.querySelector('[data-field="' + name + '"]');
            if (!field) return;
            var input = field.querySelector('.field__input');
            var old = field.querySelector('.field__error');
            if (old) old.remove();
            if (text) {
                var p = document.createElement('p');
                p.className = 'field__error';
                p.id = 'err-' + name;
                p.textContent = text;
                field.appendChild(p);
                input.setAttribute('aria-invalid', 'true');
                input.setAttribute('aria-describedby', p.id);
            } else {
                input.removeAttribute('aria-invalid');
                input.removeAttribute('aria-describedby');
            }
        }

        function validate(input) {
            var name = input.name;
            if (!messages[name]) return true;
            var value = input.value.trim();
            var ok = true;
            if (name === 'name') ok = value.length >= 2;
            if (name === 'email') ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            if (name === 'phone') ok = value === '' || /^\+?[0-9 ()-]{7,20}$/.test(value);
            if (name === 'message') ok = value.length >= 10;
            setError(name, ok ? '' : messages[name]);
            return ok;
        }

        // Validate a field when leaving it, and clear its error as soon as it's fixed
        form.querySelectorAll('.field__input').forEach(function (input) {
            input.addEventListener('blur', function () { if (input.value !== '') validate(input); });
            input.addEventListener('input', function () {
                if (input.getAttribute('aria-invalid') === 'true') validate(input);
            });
        });

        function showSent() {
            form.classList.add('is-sent');
            sent.hidden = false;
            again.hidden = false;
            sent.focus();
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            alertBox.hidden = true;

            var firstInvalid = null;
            form.querySelectorAll('.field__input').forEach(function (input) {
                if (!validate(input) && !firstInvalid) firstInvalid = input;
            });
            if (firstInvalid) {
                firstInvalid.focus();
                return;
            }

            form.classList.add('is-sending');
            submit.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json' },
                credentials: 'same-origin'
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.csrf) form.querySelector('[data-csrf]').value = data.csrf;
                if (data.ok) {
                    form.reset();
                    syncCount();
                    syncSchool();
                    showSent();
                    return;
                }
                var errors = data.errors || {};
                Object.keys(errors).forEach(function (key) {
                    if (key === 'form' || key === 'topic') {
                        alertBox.textContent = errors[key];
                        alertBox.hidden = false;
                    } else {
                        setError(key, errors[key]);
                    }
                });
                var focusTarget = form.querySelector('[aria-invalid="true"]') || alertBox;
                if (focusTarget === alertBox) alertBox.setAttribute('tabindex', '-1');
                focusTarget.focus();
            }).catch(function () {
                alertBox.textContent = 'Sorry, something went wrong. Please call or email us instead.';
                alertBox.hidden = false;
            }).then(function () {
                form.classList.remove('is-sending');
                submit.disabled = false;
            });
        });

        again.addEventListener('click', function () {
            sent.hidden = true;
            form.classList.remove('is-sent');
            form.querySelector('[name="name"]').focus();
        });

        // After a no-JS submit the page loads in the "sent" state
        if (!sent.hidden) again.hidden = false;
    }

    /* --- Click-to-load map ----------------------------------------------- */

    var map = document.querySelector('[data-map]');
    if (map) {
        var load = map.querySelector('[data-map-load]');
        load.hidden = false;
        load.addEventListener('click', function () {
            var frame = document.createElement('iframe');
            frame.src = map.getAttribute('data-embed');
            frame.title = 'Map showing the Education Office, 87 Kwame Nkrumah Avenue, Harare';
            frame.loading = 'lazy';
            frame.referrerPolicy = 'no-referrer-when-downgrade';
            frame.allowFullscreen = true;
            map.insertBefore(frame, map.firstChild);
            map.classList.add('is-loaded');
            frame.focus();
        });
    }
})();
