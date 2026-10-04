<?php
/**
 * Frequently asked questions (exclusive accordion: one open at a time).
 * Answers draw only on information published elsewhere on the site.
 */

declare(strict_types=1);

$faqs = [
    ['q' => 'How do I apply to an Anglican school?',
     'a' => 'Applications are made to your chosen school through the ministry portal. The school then reviews applications, may hold an interview, and notifies successful applicants about enrolment. See the <a href="about.php#admissions">full admissions process</a>.'],
    ['q' => 'Can I contact a school directly?',
     'a' => 'Yes. Each school may have its own admission criteria, deadlines and procedures, so it is best to confirm details with the school itself. Our <a href="institutes.php">schools directory</a> lists every Anglican school in the diocese.'],
    ['q' => 'Where can I find school fees?',
     'a' => 'Fees are set by each school and can change each term, so please ask the school you are interested in, or contact the Education Office and we will point you in the right direction.'],
    ['q' => 'Where is the Education Office?',
     'a' => e(SITE_ADDRESS[0]) . ', ' . e(SITE_ADDRESS_DETAIL) . ', ' . e(SITE_ADDRESS[1]) . '. <a href="#visit">See the map</a>.'],
    ['q' => 'How can I keep up with news and events?',
     'a' => 'Follow the official social media accounts of the Anglican Diocese of Harare and its schools, where we share news, events and updates.'],
    ['q' => 'I would like to support or partner with our schools. Who do I talk to?',
     'a' => 'We welcome partnerships and sponsorship, especially for students who need financial assistance. Send a message choosing <em>Partnerships &amp; sponsorship</em>, or call the Education Office.'],
];
?>
<section class="faq" aria-labelledby="faq-title">
    <div class="faq__inner">
        <header class="faq__header">
            <p class="section-eyebrow reveal">FAQ</p>
            <h2 class="faq__title split" id="faq-title">
                <?php foreach (['Questions,', 'answered'] as $w => $word): ?><span class="word" style="--i: <?= $w ?>"><?= e($word) ?></span> <?php endforeach; ?>
            </h2>
            <p class="faq__lead reveal">Can't find what you need? <a href="#message">Send us a message</a>.</p>
        </header>

        <div class="faq__list">
            <?php foreach ($faqs as $i => $faq): ?>
                <details class="faq__item reveal" name="faq" style="--delay: <?= $i * 60 ?>ms">
                    <summary class="faq__q">
                        <span class="faq__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                        <span><?= e($faq['q']) ?></span>
                        <span class="faq__icon" aria-hidden="true"></span>
                    </summary>
                    <div class="faq__a"><p><?= $faq['a'] /* trusted, authored above */ ?></p></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
