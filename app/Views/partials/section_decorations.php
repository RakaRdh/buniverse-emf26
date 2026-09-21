<?php
/**
 * Floating Section Decorations Partial (Cakram / Disc)
 * @param string $sectionKey (e.g. 'home', 'about-us', 'schedule', 'gallery', 'news', 'sponsor')
 * @param array  $decorations (list of active section decorations)
 */
$items = array_filter($decorations ?? [], function($d) use ($sectionKey) {
    return ($d['section_key'] ?? '') === $sectionKey;
});

if (empty($items)) {
    return;
}
?>
<div class="decoration-layer" aria-hidden="true">
    <?php foreach ($items as $item): ?>
        <?php
        $isRotated = !empty($item['is_rotated']);
        $rotDir    = $item['rotation_direction'] ?? 'clockwise';
        $rotClass  = $isRotated ? ('is-rotating rot-' . esc($rotDir)) : '';
        $mobClass  = empty($item['show_on_mobile']) ? 'hide-on-mobile' : '';
        $layout    = $item['layout_mode'] ?? 'manual';
        ?>

        <?php
        $edgeMode   = $item['edge_offset_mode'] ?? 'fixed';
        $edgeOffset = ($edgeMode === 'custom') ? ((int)($item['edge_offset_percent'] ?? 50) . '%') : '50%';
        
        $hasPerspective = !empty($item['perspective_enabled']);
        $perspVal = (int)($item['perspective_value'] ?? 800);
        $rotX     = (float)($item['rotate_x'] ?? 0);
        $rotY     = (float)($item['rotate_y'] ?? 0);
        $perspStyle = $hasPerspective ? "--dec-persp: {$perspVal}px; --dec-rot-x: {$rotX}deg; --dec-rot-y: {$rotY}deg;" : '';
        $perspClass = $hasPerspective ? 'has-perspective' : '';
        ?>

        <?php if ($layout === 'manual'): ?>
            <?php $posClass = 'pos-' . esc($item['position'] ?? 'middle-left'); ?>
            <div class="decoration-item <?= $posClass ?> <?= $rotClass ?> <?= $mobClass ?> <?= $perspClass ?>"
                 style="--dec-width: <?= (int)($item['size_px'] ?? 200) ?>px; --dec-opacity: <?= esc($item['opacity'] ?? 1.0) ?>; --dec-edge-offset: <?= $edgeOffset ?>; <?= $perspStyle ?>">
                <div class="decoration-inner-transform">
                    <img src="<?= esc($item['image_url']) ?>" alt="" loading="lazy">
                </div>
            </div>
        <?php else: ?>
            <div class="decoration-auto-group <?= $rotClass ?> <?= $mobClass ?>"
                 data-count="<?= (int)($item['auto_count'] ?? 1) ?>"
                 data-gap="<?= (int)($item['auto_gap_px'] ?? 100) ?>"
                 data-gap-unit="<?= esc($item['auto_gap_unit'] ?? 'px') ?>"
                 data-start-side="<?= esc($item['auto_start_side'] ?? 'left') ?>"
                 data-edge-offset="<?= $edgeOffset ?>"
                 data-width="<?= (int)($item['size_px'] ?? 200) ?>"
                 data-opacity="<?= esc($item['opacity'] ?? 1.0) ?>"
                 data-img="<?= esc($item['image_url']) ?>"
                 data-rotated="<?= $isRotated ? '1' : '0' ?>"
                 data-rot-dir="<?= esc($rotDir) ?>">
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
