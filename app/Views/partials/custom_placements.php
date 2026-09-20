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
        $mobClass  = empty($item['show_on_mobile']) ? 'hide-on-mobile' : '';
        $posX      = (float)($item['pos_x_percent'] ?? 50.0);
        $posY      = (float)($item['pos_y_percent'] ?? 50.0);
        $sizePx    = (int)($item['size_px'] ?? 200);
        $opacity   = (float)($item['opacity'] ?? 1.0);
        $zIndex    = (int)($item['z_index'] ?? 1);
        ?>
        <div class="custom-placement-item <?= $rotClass ?> <?= $mobClass ?>"
             style="left: <?= $posX ?>%; top: <?= $posY ?>%; --cp-width: <?= $sizePx ?>px; --cp-opacity: <?= $opacity ?>; z-index: <?= $zIndex ?>;">
            <img src="<?= esc($item['image_url']) ?>" alt="" loading="lazy">
        </div>
    <?php endforeach; ?>
</div>
