<?php
/**
 * The diocesan crest as inline SVG markup (not an image file), so the intro
 * screen can animate its parts (shield, saltire, keys, crown) individually.
 * A rendered copy for <img>/favicon use lives at
 * assets/images/brand/diocese-crest-mark.webp.
 */

declare(strict_types=1);

return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 -34 520 306" role="img" aria-labelledby="crest-title">
    <title id="crest-title">Anglican Diocese of Harare crest</title>
    <defs>
        <clipPath id="crest-shield-clip">
            <path d="M97 112H423V172C423 222 342 250 260 266C178 250 97 222 97 172Z"/>
        </clipPath>

        <!-- One key running from upper right (wards) to lower left (bow); the second key mirrors it -->
        <g id="crest-key">
            <path d="M462 133L497 128L500 150L486 152L484 143L477 144L479 154L466 156Z" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linejoin="round"/>
            <path d="M508 122L72 190" stroke="#111111" stroke-width="24" stroke-linecap="round"/>
            <path d="M508 122L72 190" stroke="#ffffff" stroke-width="16" stroke-linecap="round"/>
            <path d="M440 117L443 137M84 179L87 199" stroke="#111111" stroke-width="3" stroke-linecap="round"/>
            <circle cx="30" cy="190" r="14" fill="#ffffff" stroke="#111111" stroke-width="4"/>
            <circle cx="40" cy="214" r="14" fill="#ffffff" stroke="#111111" stroke-width="4"/>
            <circle cx="56" cy="194" r="16" fill="#ffffff" stroke="#111111" stroke-width="4"/>
            <circle cx="42" cy="199" r="6" fill="#ffffff" stroke="#111111" stroke-width="3"/>
        </g>
    </defs>

    <!-- Crossed keys behind the shield -->
    <g class="crest-key crest-key--left">
        <use href="#crest-key" xlink:href="#crest-key"/>
    </g>
    <g class="crest-key crest-key--right">
        <g transform="translate(520 0) scale(-1 1)">
            <use href="#crest-key" xlink:href="#crest-key"/>
        </g>
    </g>

    <!-- Shield: white field, red saltire, central oval -->
    <path class="crest-shield-fill" d="M97 112H423V172C423 222 342 250 260 266C178 250 97 222 97 172Z" fill="#ffffff"/>
    <g class="crest-saltire" clip-path="url(#crest-shield-clip)" stroke="#dc241c" stroke-width="38" fill="none">
        <path pathLength="1" d="M66 100L454 251"/>
        <path pathLength="1" d="M454 100L66 251"/>
    </g>
    <g class="crest-oval">
        <ellipse cx="260" cy="177" rx="38" ry="22" fill="#ffffff" stroke="#111111" stroke-width="4"/>
        <!-- dove -->
        <g fill="#111111">
            <circle cx="243" cy="178" r="4"/>
            <path d="M239 177L233 179L239 180Z"/>
            <path d="M246 180C255 187 270 187 281 180L289 183L285 175C276 171 262 172 248 176Z"/>
            <path d="M255 177C257 167 266 160 278 158C271 165 269 171 269 177Z"/>
        </g>
    </g>
    <path class="crest-shield-outline" pathLength="1" d="M97 112H423V172C423 222 342 250 260 266C178 250 97 222 97 172Z" fill="none" stroke="#111111" stroke-width="6" stroke-linejoin="round"/>

    <!-- Crown -->
    <g class="crest-crown" stroke="#111111" stroke-linecap="round" stroke-linejoin="round" fill="none">
        <path d="M132 94Q121.7 78.8 137.2 66.5Q132.9 49.8 152.3 41.2Q154.4 24.2 176.2 20.2Q184.5 4.4 206.8 5.2Q220.6 -8.3 241.8 -2.6Q260 -12.7 278.2 -2.6Q299.4 -8.3 313.2 5.2Q335.5 4.4 343.8 20.2Q365.6 24.2 367.7 41.2Q387.1 49.8 382.8 66.5Q398.3 78.8 388 94Z" fill="#ffffff" stroke-width="4"/>
        <path d="M260 -3.7V86.1M260 -3.7C222 6.9 196 41.2 190 86.1M260 -3.7C298 6.9 324 41.2 330 86.1M260 -3.7C200 -1 160 38.6 150 86.1M260 -3.7C320 -1 360 38.6 370 86.1" stroke-width="3"/>
        <path d="M149.6 79.2c2.1 -11.1 11.9 -11.1 14 0M155.2 73.7c-4.2 1.8 -4.2 8.3 0 8.3M162.1 54.3c2.1 -11.1 11.9 -11.1 14 0M167.7 48.7c-4.2 1.8 -4.2 8.3 0 8.3M185.5 34.1c2.1 -11.1 11.9 -11.1 14 0M191.1 28.5c-4.2 1.8 -4.2 8.3 0 8.3M217.1 20.9c2.1 -11.1 11.9 -11.1 14 0M222.7 15.3c-4.2 1.8 -4.2 8.3 0 8.3M288.9 20.9c2.1 -11.1 11.9 -11.1 14 0M297.3 15.3c4.2 1.8 4.2 8.3 0 8.3M320.5 34.1c2.1 -11.1 11.9 -11.1 14 0M328.9 28.5c4.2 1.8 4.2 8.3 0 8.3M343.9 54.3c2.1 -11.1 11.9 -11.1 14 0M352.3 48.7c4.2 1.8 4.2 8.3 0 8.3M356.4 79.2c2.1 -11.1 11.9 -11.1 14 0M364.8 73.7c4.2 1.8 4.2 8.3 0 8.3M181.8 80.5c1.7 -9.2 9.9 -9.2 11.6 0M186.5 75.9c-3.5 1.6 -3.5 6.9 0 6.9M196.2 60.1c1.7 -9.2 9.9 -9.2 11.6 0M200.8 55.5c-3.5 1.6 -3.5 6.9 0 6.9M222 46.1c1.7 -9.2 9.9 -9.2 11.6 0M226.6 41.5c-3.5 1.6 -3.5 6.9 0 6.9M286.4 46.1c1.7 -9.2 9.9 -9.2 11.6 0M293.4 41.5c3.5 1.6 3.5 6.9 0 6.9M312.2 60.1c1.7 -9.2 9.9 -9.2 11.6 0M319.2 55.5c3.5 1.6 3.5 6.9 0 6.9M326.6 80.5c1.7 -9.2 9.9 -9.2 11.6 0M333.5 75.9c3.5 1.6 3.5 6.9 0 6.9M217.6 80.7c1.4 -7.3 7.8 -7.3 9.2 0M221.2 77.1c-2.8 1.2 -2.8 5.4 0 5.4M239.7 65.9c1.4 -7.3 7.8 -7.3 9.2 0M243.4 62.2c-2.8 1.2 -2.8 5.4 0 5.4M271.1 65.9c1.4 -7.3 7.8 -7.3 9.2 0M276.6 62.2c2.8 1.2 2.8 5.4 0 5.4M293.2 80.7c1.4 -7.3 7.8 -7.3 9.2 0M298.8 77.1c2.8 1.2 2.8 5.4 0 5.4" stroke-width="2.4"/>
        <g class="crest-pearls" fill="#111111" stroke="none"><circle cx="260" cy="12.2" r="3.3"/><circle cx="260" cy="30.6" r="3.3"/><circle cx="260" cy="49.1" r="3.3"/><circle cx="260" cy="67.6" r="3.3"/><circle cx="236.7" cy="6.6" r="3.3"/><circle cx="219.1" cy="21.3" r="3.3"/><circle cx="205.1" cy="40.3" r="3.3"/><circle cx="195.1" cy="63" r="3.3"/><circle cx="283.3" cy="6.6" r="3.3"/><circle cx="300.9" cy="21.3" r="3.3"/><circle cx="314.9" cy="40.3" r="3.3"/><circle cx="324.9" cy="63" r="3.3"/></g>
        <!-- jewelled band -->
        <path d="M126 113L134 88H386L394 113Z" fill="#ffffff" stroke-width="4"/>
        <g fill="#111111" stroke="none">
            <ellipse cx="166" cy="100.5" rx="10" ry="5.5"/><ellipse cx="213" cy="100.5" rx="10" ry="5.5"/><ellipse cx="260" cy="100.5" rx="11" ry="6"/><ellipse cx="307" cy="100.5" rx="10" ry="5.5"/><ellipse cx="354" cy="100.5" rx="10" ry="5.5"/>
            <circle cx="142" cy="100.5" r="2.6"/><circle cx="189.5" cy="100.5" r="2.6"/><circle cx="236.5" cy="100.5" r="2.6"/><circle cx="283.5" cy="100.5" r="2.6"/><circle cx="330.5" cy="100.5" r="2.6"/><circle cx="378" cy="100.5" r="2.6"/>
        </g>
        <!-- orb and cross -->
        <circle cx="260" cy="-10.7" r="6.5" fill="#ffffff" stroke-width="3"/>
        <path d="M260 -29.7V-16.7M254 -23.7H266" stroke-width="3.5"/>
    </g>
</svg>
SVG;
