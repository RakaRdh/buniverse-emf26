<?php
/**
 * Custom Placement Partial (Freeform Coordinates)
 * @param string $sectionKey (e.g. 'home', 'about-us', 'schedule', 'gallery', 'news', 'sponsor')
 * @param array  $customPlacements
 */
$items = array_filter($customPlacements ?? [], function($p) use ($sectionKey) {
    return ($p['section_key'] ?? '') === $sectionKey;
});

if (empty($items)) {
    return;
}
?>
<div class="custom-placement-layer" aria-hidden="true">
    <?php foreach ($items as $item): ?>
        <?php
        $isRotated = !empty($item['is_rotated']);
        $rotDir    = $item['rotation_direction'] ?? 'clockwise';
        $rotClass  = $isRotated ? ('is-rotating rot-' . esc($rotDir)) : '';
        
        $devVis = $item['device_visibility'] ?? (!empty($item['show_on_mobile']) ? 'all' : 'desktop_only');
        if ($devVis === 'desktop_only') {
            $deviceClass = 'hide-on-mobile';
        } elseif ($devVis === 'mobile_only') {
            $deviceClass = 'hide-on-desktop';
        } else {
            $deviceClass = '';
        }

        $posX      = (float)($item['pos_x_percent'] ?? 50.0);
        $posY      = (float)($item['pos_y_percent'] ?? 50.0);
        $sizePx    = (int)($item['size_px'] ?? 200);
        $opacity   = (float)($item['opacity'] ?? 1.0);
        $zIndex    = (int)($item['z_index'] ?? 1);
        $tintColor = !empty($item['tint_color']) ? trim($item['tint_color']) : null;
        $whiteMaskUrl = base_url('assets/img/decoration_white.png');

        $hasPerspective = !empty($item['perspective_enabled']);
        $perspVal = (int)($item['perspective_value'] ?? 800);
        $rotX     = (float)($item['rotate_x'] ?? 0);
        $rotY     = (float)($item['rotate_y'] ?? 0);
        $rotZ     = (float)($item['rotate_z'] ?? 0);
        $perspStyle = $hasPerspective ? "--cp-persp: {$perspVal}px; --cp-rot-x: {$rotX}deg; --cp-rot-y: {$rotY}deg; --cp-rot-z: {$rotZ}deg;" : "--cp-rot-z: {$rotZ}deg;";
        $perspClass = $hasPerspective ? 'has-perspective' : '';
        ?>
        <div class="custom-placement-item <?= $rotClass ?> <?= $deviceClass ?> <?= $perspClass ?>"
             style="left: <?= $posX ?>%; top: <?= $posY ?>%; --cp-width: <?= $sizePx ?>px; --cp-opacity: <?= $opacity ?>; z-index: <?= $zIndex ?>; <?= $perspStyle ?>">
            <div class="custom-placement-inner">
                <?php if ($tintColor): ?>
                    <div class="decoration-tint-mask" 
                         style="--tint-color: <?= esc($tintColor) ?>; --mask-url: url('<?= esc($whiteMaskUrl) ?>'); width: 100%; height: 100%;"></div>
                <?php else: ?>
                    <img src="<?= esc($item['image_url']) ?>" alt="" loading="lazy">
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
