<?php
/*
 * Schools. Sources: the diocese's own school pages (previous site) unless
 * noted; St John's from sjch.ac.zw; directory facts (ibzim.com,
 * openclass.co.zw) only where the diocese has not published details.
 * Fees and phone numbers are deliberately left out: they change termly.
 *
 * results: year => [O Level %, A Level %] (null = not published);
 * for primary schools the single value is the Grade 7 pass rate.
 */

declare(strict_types=1);

return [
    [
        'id' => 'langham', 'name' => 'Langham Girls High School', 'level' => 'secondary', 'tag' => 'Secondary · Girls · Boarding',
        'place' => 'Hasfa Farm, Mvurwi (Mazowe District)',
        'motto' => null,
        'text' => 'Since 1934 Cathrine Langham ran a home craft centre for women on Hasfa Farm. She donated the farm to the Anglican Church to start a girls-only high school, and Langham Girls High School opened in 1983. A Level followed in 2002 (Commercials and Arts), with Sciences added in 2017.',
        'facts' => [
            'Founded' => '1983',
            'Location' => 'Hasfa Farm, bordering Chiweshe communal area, about 100 km from Harare',
            'Learners' => '498 girls (18 day scholars)',
            'Staff' => '27 teachers · 23 ancillary',
            'Levels' => 'O Level (ZIMSEC) · A Level (Cambridge)',
        ],
        'highlights' => [
            'Registered as an A Level Cambridge Centre in 2023',
            'Also offers a French Diploma and HEXCO examinations',
            '92.05% O Level pass rate in 2019, ranked 14th best O Level school',
            'Run by a Board of Governors appointed by the Bishop of Harare',
        ],
        'results' => [],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/langham', 'caption' => 'Langham learners at the school sign']],
    ],
    [
        'id' => 'st-johns-chikwaka', 'name' => "St John's High School, Chikwaka", 'level' => 'secondary', 'tag' => 'Secondary',
        'place' => 'Juru, Goromonzi District, Mashonaland East',
        'motto' => 'In Pursuit of Excellence',
        'text' => "Founded in 1972 as a small rural school, St John's grew through the 1980s and 1990s into one of Mashonaland East's respected centres of academic and character development, and marked 50 years in 2022. It aims to produce graduates with academic, vocational and entrepreneurial skills for a fast-changing world of work.",
        'facts' => [
            'Founded' => '1972',
            'Location' => 'Juru, Goromonzi District',
            'Learners' => '900+',
            'Staff' => '60+',
            'Focus' => 'Academic, technical-vocational and entrepreneurial',
        ],
        'highlights' => [
            'Commercial biogas plant powering the dining hall and piggery',
            'Broiler project producing at least 6,000 birds per batch',
            '12 vocational subjects alongside the academic curriculum',
            'Values: integrity, respect, discipline, faith, excellence, leadership, compassion, responsibility',
        ],
        'results' => [],
        'website' => 'https://www.sjch.ac.zw/',
        'gallery' => [['image' => 'assets/images/about-page/biogas', 'caption' => 'The school\'s biogas digester'], ['image' => 'assets/images/about-page/poultry', 'caption' => 'The broiler poultry project']],
    ],
    [
        'id' => 'st-phillips-mangwenya', 'name' => 'St Phillips High School, Mangwenya', 'level' => 'secondary', 'tag' => 'Secondary · Boarding',
        'place' => 'Guruve District, Mashonaland Central',
        'motto' => null,
        'text' => 'A mixed boarding school about 150 km from Harare, reached by tarred road. The range and quality of its extracurricular activities, including sports, arts, music, drama, debate and community service, is commendable.',
        'facts' => [
            'Location' => 'Guruve, about 150 km from Harare',
            'Type' => 'Mixed boarding',
            'Levels' => 'O Level and A Level (ZIMSEC)',
        ],
        'highlights' => [
            '100% A Level pass rate in 2024 (directory report)',
            "Received the 2024 Secretary's Merit Award in its province (directory report)",
        ],
        'results' => [],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/mangwenya-bus', 'caption' => 'The St Phillips Magwenya school bus']],
    ],
    [
        'id' => 'st-marys-chitungwiza', 'name' => "St Mary's High School, Chitungwiza", 'level' => 'secondary', 'tag' => 'Secondary',
        'place' => 'Chitungwiza, Harare Province',
        'motto' => null,
        'text' => "Founded in 1962 on Christian principles with 70 boarding boys and three teachers, St Mary's welcomed its first girls in 1975. It aims to produce well-rounded, morally upright citizens guided by the virtues of Unhu/Ubuntu, and its choir has won many trophies in church competitions.",
        'facts' => [
            'Founded' => '1962',
            'Learners' => '1,268 (605 boys · 663 girls)',
            'Staff' => '53 teachers · 11 ancillary',
            'Special classes' => '34 learners',
        ],
        'highlights' => [
            '2024: 85% at O Level and 95.3% at A Level',
            'Best A Level candidate scored 22 points; 12 candidates scored 15+',
            'Best O Level candidate achieved 9 As and 1 B',
        ],
        'results' => ['2018' => [76.52, 91.76], '2019' => [72.2, 96.46], '2020' => [75, 86], '2021' => [61.04, 95.5], '2022' => [64.7, 95.9], '2023' => [65.78, 98.3], '2024' => [85, 95.3]],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/st-marys-admin', 'caption' => 'The administration block'], ['image' => 'assets/images/institutes/st-marys-bus', 'caption' => 'The St Mary\'s High School bus'], ['image' => 'assets/images/institutes/st-marys-grounds', 'caption' => 'Classroom block and grounds']],
    ],
    [
        'id' => 'st-marks', 'name' => "St Mark's High School", 'level' => 'secondary', 'tag' => 'Secondary · Mission · Boarding',
        'place' => 'Mhondoro, Chegutu District, Mashonaland West',
        'motto' => 'Let the Light Shine',
        'text' => "An Anglican mission school founded in 1982. It began as a day school and became a boarding school in 2005 with 250 pupils. Spiritual life is central: two Eucharist services every Sunday, a midweek service, and Saturday guilds (St Agnes for girls, St Peter's for boys, servers and choir).",
        'facts' => [
            'Founded' => '1982 (boarding since 2005)',
            'Learners' => '879 (765 boarders)',
            'Staff' => '43 teachers · 26 ancillary',
            'Levels' => 'O Level and A Level',
        ],
        'highlights' => [
            '100% A Level pass rate in 2024',
            '79 students baptised and 74 confirmed in 2024',
            'Ranked 34th among A Level schools in 2014 (97.92%)',
        ],
        'results' => ['2020' => [61, 98], '2021' => [57, 98.6], '2022' => [70, 92], '2023' => [79, 92], '2024' => [78, 100]],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/st-marks-students', 'caption' => 'St Mark\'s learners in school tracksuits'], ['image' => 'assets/images/institutes/st-marks-bus', 'caption' => 'Learners beside the St Mark\'s Diocesan bus'], ['image' => 'assets/images/institutes/st-marks-eucharist', 'caption' => 'A Eucharist service at St Mark\'s']],
    ],
    [
        'id' => 'st-oswalds', 'name' => "St Oswald's High School", 'level' => 'secondary', 'tag' => 'Secondary · Mission · Boarding',
        'place' => 'Zimhindo Village, Ward 3, Mhondoro-Ngezi',
        'motto' => null,
        'text' => "Established in 1982 under the Anglican Diocese of Harare and developed into a boarding school in 2018. In line with the Heritage-Based Curriculum (2024–2030) it now offers pure sciences at O and A Level and tech-vocational HEXCO courses, guided by the values of oneness, accountability and transparency.",
        'facts' => [
            'Founded' => '1982 (boarding since 2018)',
            'Learners' => '867 (416 boys · 451 girls)',
            'Boarders' => '320 (boarding full)',
            'Levels' => 'O Level and A Level',
        ],
        'highlights' => [
            'New dining hall opened by the Rt Rev Dr Farai Mutamiri, 21 September 2024',
            'Business section since 2022: gardening, poultry, piggery and goats',
            'Solar-powered borehole and refurbished Wi-Fi computer lab (2024)',
            'Trophies in handball, volleyball, soccer, netball and athletics (NASH & ASSA)',
        ],
        'results' => [],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/st-oswalds-sign', 'caption' => 'The St Oswald\'s Mission agri-business sign'], ['image' => 'assets/images/institutes/st-oswalds-campus', 'caption' => 'Classroom blocks on the campus'], ['image' => 'assets/images/institutes/st-oswalds-hall', 'caption' => 'A school building with its blue roof'], ['image' => 'assets/images/institutes/st-oswalds-bus', 'caption' => 'The St Oswald\'s school bus'], ['image' => 'assets/images/institutes/st-oswalds-goats', 'caption' => 'Goats from the business section']],
    ],
    [
        'id' => 'st-clares', 'name' => "St Clare's Primary School", 'level' => 'primary', 'tag' => 'Primary',
        'place' => 'Hanyanga Village, Ward 16, Murewa',
        'motto' => null,
        'text' => "St Clare's follows a curriculum that adheres to national standards while adding subjects and activities that reflect the school's values and mission. Parents' project levies have funded major improvements.",
        'facts' => [
            'Location' => 'Hanyanga Village, Ward 16, Murewa',
            'Levels' => 'ECD to Grade 7',
        ],
        'highlights' => [
            'Two-classroom ECD block built in 2019 (US$40,000, from parent levies)',
            '160 learner chairs and tables purchased (US$7,200)',
            'Borehole with a 5,000-litre tank',
            'The 2023 dip followed COVID-era school closures',
        ],
        'results' => ['2020' => [76], '2021' => [73], '2022' => [66], '2023' => [46], '2024' => [55]],
        'website' => null,
        'gallery' => [],
    ],
    [
        'id' => 'st-marys-primary', 'name' => "St Mary's Primary School", 'level' => 'primary', 'tag' => 'Primary',
        'place' => 'Anglican Diocese of Harare',
        'motto' => null,
        'text' => 'Committed to holistic education grounded in Christian values, with a focus on academic excellence, character development and moral integrity, and dedicated staff giving each student personalised support.',
        'facts' => [],
        'highlights' => [],
        'results' => [],
        'website' => null,
        'gallery' => [['image' => 'assets/images/institutes/st-marys-primary-bus', 'caption' => 'The St Mary\'s Anglican Primary School bus']],
    ],
];
