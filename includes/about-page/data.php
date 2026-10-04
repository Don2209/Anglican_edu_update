<?php
/**
 * Content for about.php. Sourced from the previous site's About, Institutes,
 * Academics, Sports and Projects pages (lightly edited for spelling/grammar).
 */

declare(strict_types=1);

$img = 'assets/images/about-page/';

return [
    // The four chapters, clockwise around the shield (top, right, bottom, left)
    'chapters' => [
        'institutions' => ['n' => '01', 'title' => 'Institutions', 'short' => 'Schools',   'image' => $img . 'institutions'],
        'academics'    => ['n' => '02', 'title' => 'Academics',    'short' => 'Learning',  'image' => 'assets/images/gallery/gallery-science-lab'],
        'sports'       => ['n' => '03', 'title' => 'Sports & Culture', 'short' => 'Sport', 'image' => $img . 'track'],
        'projects'     => ['n' => '04', 'title' => 'Projects',     'short' => 'Projects',  'image' => $img . 'poultry'],
    ],

    'values' => [
        [
            'title' => 'Governance',
            'text'  => 'The Education Department operates as a central governing body, ensuring effective management, coordination and support for all Anglican schools within the Harare Diocese. It establishes policies, guidelines and standards for academic excellence, curriculum development and educational best practice, working with school administrators, teachers and stakeholders to enhance the learning experience.',
        ],
        [
            'title' => 'Christian Culture',
            'text'  => 'Christian principles are integrated into the curriculum, creating an environment that encourages moral and ethical development. Regular chapel services, Bible studies and Christian clubs give students opportunities for worship, spiritual growth and understanding of Anglican traditions, alongside community service, compassion and outreach.',
        ],
        [
            'title' => 'Affordability',
            'text'  => 'Quality education should be within reach. The department implements transparent fee structures and financial aid programmes so that students from diverse socio-economic backgrounds have equal opportunities, and actively seeks partnerships and sponsorship for students who need financial assistance.',
        ],
    ],

    // Shared with institutes.php
    'schools' => require dirname(__DIR__) . '/data/schools.php',

    'admissions' => [
        ['title' => 'Application',            'text' => 'Students or their parents/guardians submit an application form to their chosen school through the ministry portal, with personal details, contact information, academic history and any required documents.'],
        ['title' => 'Application review',     'text' => "The school's admissions committee reviews each application, considering the student's academic record, conduct and any special circumstances mentioned."],
        ['title' => 'Interview',              'text' => 'Some schools interview prospective students and their parents/guardians to understand their suitability and alignment with the school\'s values.'],
        ['title' => 'Acceptance & enrolment', 'text' => 'Successful applicants are notified, then complete enrolment by submitting further documents, paying any necessary fees and signing the relevant agreements.'],
        ['title' => 'Waitlist & appeals',     'text' => 'When applications exceed available places, schools may keep a waitlist. Some also allow appeals where there are extenuating circumstances or new information.'],
    ],

    'features' => [
        ['icon' => 'book',    'title' => 'Curriculum',            'text' => 'National educational standards, enriched with subjects and activities that reflect each school\'s values: mathematics, sciences, languages, social studies, arts and physical education.'],
        ['icon' => 'spark',   'title' => 'Extracurricular',       'text' => 'Clubs, societies and programmes in sport, arts, music, drama, debate, public speaking, community service and leadership.'],
        ['icon' => 'path',    'title' => 'Specialised programmes', 'text' => 'Some schools offer specialised tracks, international curricula such as Cambridge International Examinations, or vocational training for specific careers.'],
        ['icon' => 'cross',   'title' => 'Spiritual education',   'text' => 'Religious studies, chapel services and prayer foster moral and spiritual development alongside intellectual growth.'],
        ['icon' => 'chip',    'title' => 'Technology',            'text' => 'Well-equipped computer labs, digital resources and technology-enabled teaching enhance the learning experience.'],
        ['icon' => 'hand',    'title' => 'Support services',      'text' => 'Remedial classes, tutoring, counselling and special education support make sure every student can succeed.'],
    ],

    'subjects' => [
        'primary' => ['label' => 'Primary', 'list' => ['Mathematics', 'English', 'Shona', 'FAREME', 'Physical Education', 'Agricultural and Environmental Science', 'Moral and Religious Studies', 'Music', 'Craft and Art']],
        'ordinary' => ['label' => 'O Level', 'list' => ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'History', 'Accounting', 'Shona', 'English', 'Shona Literature', 'English Literature', 'Computer Science', 'Hexco', 'Geography', 'FRS', 'Agriculture', 'Building', 'Food and Nutrition', 'Fashion and Fabrics', 'Pure Mathematics', 'Additional Mathematics', 'PESMD', 'Technical Graphics']],
        'advanced' => ['label' => 'A Level', 'list' => ['Biology', 'Geography', 'Physics', 'FRS', 'History', 'Pure Mathematics', 'Chemistry', 'Accounting', 'Economics', 'Business Studies', 'Statistics', 'Shona Literature', 'Divinity', 'Sociology']],
    ],

    'abbreviations' => [
        'FAREME' => 'Family, Religion and Moral Education',
        'FRS'    => 'Family and Religious Studies',
        'PESMD'  => 'Physical Education, Sport and Mass Displays',
    ],

    'sports'  => ['Athletics', 'Soccer', 'Cricket', 'Netball', 'Basketball', 'Tennis', 'Volleyball'],
    'culture' => ['Music', 'Drama', 'Art', 'Debate', 'Public Speaking', 'Community Service', 'Leadership'],

    'events' => [
        [
            'day' => '14', 'month' => 'Feb', 'year' => '2025',
            'title' => 'ASSA Athletics 2025',
            'place' => 'Belvedere Teachers College',
            'text' => 'All schools in the Harare Diocese competed in the Anglican Schools Athletics Competitions, showcasing talent across track and field. Top athletes were selected to represent the diocese at the NASSA competitions on 27 March 2025, also at Belvedere Teachers College.',
            'image' => $img . 'relay-heat',
            'alt' => 'Athletes racing down the home straight of a running track',
        ],
        [
            'day' => '—', 'month' => '', 'year' => '2025',
            'title' => 'Schools Leadership Training',
            'place' => 'All diocesan schools',
            'text' => 'A leadership programme for school prefects promoted a consistent leadership culture, building communication and teamwork skills with participation from every school. Follow-up sessions and mentorship are recommended for ongoing support.',
            'image' => $img . 'leadership',
            'alt' => 'School prefects in blazers seated in a marquee during leadership training',
        ],
    ],

    'strip' => [
        ['image' => $img . 'track',          'tag' => 'Athletics', 'caption' => 'Neck and neck on the home straight', 'alt' => 'Two athletes sprinting on a stadium track'],
        ['image' => $img . 'stands',         'tag' => 'Athletics', 'caption' => 'Schools filling the stands on meet day', 'alt' => 'Students filling the stands at an athletics meet'],
        ['image' => 'assets/images/gallery/gallery-marimba', 'tag' => 'Music', 'caption' => 'Marimba practice in the music room', 'alt' => 'Students playing marimbas'],
        ['image' => $img . 'teams',          'tag' => 'Teamwork', 'caption' => 'School teams lined up on the track', 'alt' => 'School teams lined up on the track'],
        ['image' => $img . 'prize-giving',   'tag' => 'Soccer', 'caption' => 'Honouring a young footballer', 'alt' => 'A young footballer receiving a prize from officials'],
        ['image' => $img . 'athletes-group', 'tag' => 'Community', 'caption' => 'Athletes and coaches together', 'alt' => 'Athletes in team colours gathered with their coaches'],
    ],

    'projects' => [
        [
            'kicker' => 'Practicals', 'title' => 'Schools Practicals', 'image' => $img . 'practicals',
            'alt' => 'Students in overalls laying bricks for a building project',
            'text' => 'Improving practical subjects equips students with real-world skills. Food and Nutrition promotes healthy eating, Fashion and Fabrics builds design skills, Building and Block Laying offers hands-on construction experience, Computer Science prepares students for a tech-driven future, and Agriculture fosters environmental awareness.',
        ],
        [
            'kicker' => 'Biogas Digester', 'title' => "Green energy at St John's Chikwaka", 'image' => $img . 'biogas',
            'alt' => 'The domed covers of an underground biogas digester',
            'text' => "Why throw waste away when it can be recycled? St John's High School, Chikwaka built a commercial biogas plant that produces methane gas for cooking in the dining hall, plus lighting and heat lamps for the piggery. Students helped build it, gaining industry-ready experience.",
        ],
        [
            'kicker' => 'Poultry', 'title' => '6,000 broilers a batch', 'image' => $img . 'poultry',
            'alt' => 'Hundreds of white broiler chickens in a poultry house',
            'text' => "St John's High School, Chikwaka produces at least six thousand broiler chickens per batch, selling them to the local market at wholesale prices and adding them to students' diets. The income supports the school so it relies less on fees, and students take part in brooding to learn agribusiness first-hand.",
        ],
        [
            'kicker' => 'Piggery', 'title' => 'Income-generating projects', 'image' => $img . 'piggery',
            'alt' => 'Piglets resting in a pen',
            'text' => 'Many of our schools have entered agribusiness with remarkable results, especially piggery: one sow can produce up to 18 piglets with good care. It puts fresh pork on boarding-school menus, and students learn to manage the project, applying what they study in class.',
        ],
        [
            'kicker' => 'Schools Logistics', 'title' => 'New school buses', 'image' => $img . 'buses',
            'alt' => 'The Bishop smiling from the driver\'s seat of a new school bus',
            'text' => 'In 2025 the Diocese of Harare Education Office acquired modern, state-of-the-art buses for its schools, each branded with the school name and the diocesan logo, improving student mobility and reflecting pride and unity.',
        ],
        [
            'kicker' => 'IT', 'title' => 'Technology at Langham', 'image' => $img . 'langham',
            'alt' => 'Langham Girls High School students in front of the school sign',
            'text' => 'Langham Girls High School integrates technology into its academic programme with a well-equipped computer lab, digital resources and technology-enabled teaching, and consistently produces strong results, including a 92.05% O-Level pass rate in 2019.',
        ],
        [
            'kicker' => 'Solar Power', 'title' => 'Go Green projects', 'image' => $img . 'solar',
            'alt' => 'A solar inverter and battery system mounted on a wall',
            'text' => 'Schools are switching from fossil fuel to solar energy, using sunlight to power lighting so students can study at night. Some have moved all school lighting to solar to cut fluctuating generator fuel costs, alongside health and wellness initiatives for the wider community.',
        ],
        [
            'kicker' => 'Best Results', 'title' => 'Quality education', 'image' => 'assets/images/hero/hero-academics',
            'alt' => 'Students in maroon blazers concentrating during a maths lesson',
            'text' => 'The Education Department oversees every Anglican school in the Harare Diocese, from primary to secondary, providing quality education while nurturing Christian values, fostering inclusivity and promoting holistic development.',
        ],
    ],
];
