<?php
// Generates stylized clean placeholder SVGs for speakers, logos, gallery, etc.
$public = __DIR__ . '/public/assets/images';

function make_speaker_svg($name, $role, $outPath) {
    $initials = '';
    $parts = explode(' ', $name);
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= strtoupper(substr($p, 0, 1));
    }
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="100%" height="100%">
  <defs>
    <radialGradient id="bgGrad" cx="50%" cy="40%" r="65%" fx="50%" fy="30%">
      <stop offset="0%" stop-color="#147B9C"/>
      <stop offset="60%" stop-color="#094A62"/>
      <stop offset="100%" stop-color="#04222E"/>
    </radialGradient>
    <linearGradient id="bodyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1E293B"/>
      <stop offset="100%" stop-color="#0F172A"/>
    </linearGradient>
  </defs>
  <rect width="400" height="400" fill="url(#bgGrad)"/>
  <circle cx="200" cy="155" r="70" fill="#E2E8F0" opacity="0.9"/>
  <path d="M100 370 C100 270, 140 250, 200 250 C260 250, 300 270, 300 370 Z" fill="url(#bodyGrad)"/>
  <circle cx="200" cy="155" r="70" fill="none" stroke="#38BDF8" stroke-width="2" opacity="0.4"/>
  <text x="200" y="170" font-family="'Plus Jakarta Sans', sans-serif" font-size="44" font-weight="bold" fill="#0369A1" text-anchor="middle">$initials</text>
  <rect x="20" y="340" width="360" height="42" rx="8" fill="#031E2A" fill-opacity="0.8"/>
  <text x="200" y="367" font-family="'Plus Jakarta Sans', sans-serif" font-size="16" font-weight="600" fill="#FFFFFF" text-anchor="middle">$safeName</text>
</svg>
SVG;
    file_put_contents($outPath, $svg);
}

function make_logo_svg($text, $outPath, $w = 200, $h = 60, $sub = '') {
    $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $subSvg = '';
    $y = $h / 2 + 6;
    if ($sub) {
        $y = $h / 2 - 2;
        $safeSub = htmlspecialchars($sub, ENT_QUOTES, 'UTF-8');
        $subY = $h / 2 + 14;
        $subSvg = "<text x=\"50%\" y=\"$subY\" font-family=\"'Plus Jakarta Sans', sans-serif\" font-size=\"9\" font-weight=\"400\" fill=\"#94A3B8\" text-anchor=\"middle\" letter-spacing=\"1\">$safeSub</text>";
    }
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 $w $h" width="$w" height="$h">
  <text x="50%" y="$y" font-family="'Plus Jakarta Sans', 'Inter', sans-serif" font-size="16" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="1">$safeText</text>
  $subSvg
</svg>
SVG;
    file_put_contents($outPath, $svg);
}

function make_gallery_svg($title, $sub, $outPath, $w = 800, $h = 500) {
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 $w $h" width="$w" height="$h">
  <defs>
    <linearGradient id="gGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1E293B"/>
      <stop offset="50%" stop-color="#0F172A"/>
      <stop offset="100%" stop-color="#06121D"/>
    </linearGradient>
  </defs>
  <rect width="$w" height="$h" fill="url(#gGrad)"/>
  <circle cx="20%" cy="30%" r="80" fill="#0284C7" opacity="0.15"/>
  <circle cx="80%" cy="70%" r="120" fill="#0EA5E9" opacity="0.1"/>
  <path d="M0 $h L150 280 L320 380 L500 240 L700 370 L$w 290 L$w $h Z" fill="#0B2333" opacity="0.6"/>
  <text x="50%" y="45%" font-family="'Plus Jakarta Sans', sans-serif" font-size="22" font-weight="700" fill="#F8FAFC" text-anchor="middle">$title</text>
  <text x="50%" y="55%" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="400" fill="#94A3B8" text-anchor="middle">$sub</text>
</svg>
SVG;
    file_put_contents($outPath, $svg);
}

// Generate speakers
$speakers = [
    'enggartiasto_lukita' => ['Enggartiasto Lukita', 'Executive Chairman B-Universe'],
    'rosan_roeslani' => ['Rosan Roeslani', 'Minister of Investment'],
    'prabowo_subianto' => ['Prabowo Subianto', 'President of Indonesia'],
    'hashim_djojohadikusumo' => ['Hashim Djojohadikusumo', 'Special Envoy'],
    'bahlil_lahadalia' => ['Bahlil Lahadalia', 'Minister of Energy & Mineral Resources'],
    'simon_aloysius_mantiri' => ['Simon Aloysius Mantiri', 'President Director PT Pertamina'],
    'darmawan_prasodjo' => ['Darmawan Prasodjo', 'President Director PT PLN'],
    'bernardus_imanto' => ['Bernardus Imanto', 'President Director PT Vale Indonesia'],
    'tony_wenas' => ['Tony Wenas', 'President Director PT Freeport Indonesia'],
    'rachmat_makkasau' => ['Rachmat Makkasau', 'President Director PT Amman Mineral'],
    'iwan_dewono_budiyuwono' => ['Iwan Dewono Budiyuwono', 'President Director PT Alamtri Resources'],
    'pramono_anung' => ['Pramono Anung', 'Governor of Jakarta'],
    'moderator_djaka_susila' => ['Djaka Susila', 'Editor In Chief - Integrated Investor'],
];

foreach ($speakers as $file => $info) {
    make_speaker_svg($info[0], $info[1], "$public/speakers/$file.svg");
}

// Generate sponsor logos
$sponsors = [
    'bni' => ['BNI', 190, 58, ''],
    'astra' => ['ASTRA', 200, 58, ''],
    'pluang' => ['Pluang', 170, 50, 'INVESTMENT APP'],
    'pupuk_indonesia' => ['PUPUK INDONESIA', 180, 52, 'HOLDING COMPANY'],
    'idx' => ['IDX', 95, 45, 'Indonesia Stock Exchange'],
    'cleo' => ['Cleo', 85, 42, 'Pure Water'],
    'pln' => ['PLN', 135, 44, 'Listrik Untuk Kehidupan'],
    'pertamina_gas' => ['PERTAMINA GAS', 165, 44, 'GAS NEGARA'],
    'jalin' => ['jalin', 105, 40, 'Member of Danareksa'],
    'icdx' => ['ICDX GROUP', 110, 36, ''],
    'staco_mandiri' => ['STACO MANDIRI', 115, 36, 'ASURANSI'],
    'petrodrill' => ['petrodrill', 125, 36, 'Manufaktur Indonesia'],
    'batamec' => ['BATAMEC', 110, 36, 'SHIPYARD'],
    'noahtu' => ['NOAHTU', 100, 36, 'SHIPYARD'],
    'merpati' => ['MERPATI', 110, 36, 'MAINTENANCE FACILITY'],
    'telkom' => ['Telkom Indonesia', 110, 34, 'the world in your hand'],
    'jamkrindo' => ['Jamkrindo', 120, 34, 'A member of IFG'],
    'rmke' => ['RMKE', 95, 34, 'ENERGY LOGISTICS'],
    'telkomsel' => ['Telkomsel', 115, 34, 'by Telkom Indonesia'],
    'intibios' => ['Intibios Stem Cell', 150, 34, 'Wellness Therapy'],
    'regenic' => ['Regenic', 100, 34, 'Stem Cell by Kalbe'],
    'kencana' => ['KENCANA', 110, 32, 'AHLINYA BAJA RINGAN'],
    'ambeven' => ['Ambeven', 105, 32, 'Kapsul Untuk Wasir'],
    'pertamina_kilang' => ['PERTAMINA KILANG', 145, 32, 'INTERNASIONAL'],
    'ifg' => ['IFG', 75, 32, 'FINANCIAL GROUP'],
    'el_hotel' => ['éL HOTEL GROUP', 85, 36, ''],
    'nice' => ['NICE', 95, 36, 'NUSANTARA CONVENTION'],
    'cityvision' => ['cityvision', 95, 36, 'OUT OF HOME MEDIA'],
    'pixel' => ['Pixel', 70, 36, 'LED DISPLAY'],
    'sspace' => ['SSPACE', 85, 36, 'EVENT SPACE'],
];

foreach ($sponsors as $file => $info) {
    make_logo_svg($info[0], "$public/sponsors/$file.svg", $info[1], $info[2], $info[3]);
}

// Generate Gallery SVGs
make_gallery_svg("VIP Dignitaries Gathering", "President Joko Widodo, Ministers & Delegates Walking to Plenary Hall", "$public/gallery/gallery_featured.svg", 1000, 560);
make_gallery_svg("VIP Session 1", "Dignitaries Escort", "$public/gallery/gallery_thumb_1.svg", 360, 220);
make_gallery_svg("Executive Discussion", "Ministers Bilateral Meeting", "$public/gallery/gallery_thumb_2.svg", 360, 220);
make_gallery_svg("Summit Opening", "Delegation Greeting Guests", "$public/gallery/gallery_thumb_3.svg", 360, 220);

// Generate Related News SVGs
make_gallery_svg("Investor Day 2026", "Adrie Basuki Meraup Cuan dari Limbah Fashion", "$public/news/news_1.svg", 450, 280);
make_gallery_svg("Green Economy 2026", "Hilirisasi Berkelanjutan & Ekosistem Industri", "$public/news/news_2.svg", 450, 280);
make_gallery_svg("Fiscal Strategy 2026", "Target Pertumbuhan 8% Menuju Indonesia Emas", "$public/news/news_3.svg", 450, 280);

echo "All SVGs generated successfully.\n";
