<?php
$menuDef    = menu();
$activePath = $menu_active ?? active_menu_path($menuDef);
$shown      = [];
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-mark">
            <img src="<?= url('assets/img/logo.png') ?>" alt="<?= e(config('app_name')) ?>" class="logo">
        </span>
        <div>
            <div class="brand-name"><?= e(config('app_name')) ?></div>
            <div class="brand-sub">Information Management System</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menuDef as $entry): ?>
            <?php if (!empty($entry['roles']) && !Auth::hasRole($entry['roles'])) { continue; } ?>

            <?php if (!empty($entry['section']) && !in_array($entry['section'], $shown, true)): ?>
                <?php $shown[] = $entry['section']; ?>
                <div class="nav-label"><?= e($entry['section']) ?></div>
            <?php endif; ?>

            <?php if ($entry['type'] === 'link'): ?>
                <a href="<?= url($entry['path']) ?>" class="nav-link-item <?= $activePath === $entry['path'] ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-<?= e($entry['icon'] ?? 'circle') ?>" aria-hidden="true"></i></span>
                    <?= e($entry['label']) ?>
                </a>
                <?php continue; ?>
            <?php endif; ?>

            <?php $open = in_array($activePath, array_column($entry['items'], 1), true); ?>
            <button class="nav-group-toggle <?= $open ? 'has-active' : '' ?>" type="button"
                    data-bs-toggle="collapse" data-bs-target="#grp-<?= e($entry['id']) ?>"
                    aria-expanded="<?= $open ? 'true' : 'false' ?>">
                <span class="nav-icon"><i class="bi bi-<?= e($entry['icon'] ?? 'circle') ?>" aria-hidden="true"></i></span>
                <span class="nav-text"><?= e($entry['label']) ?></span>
                <span class="nav-chevron"></span>
            </button>
            <div class="collapse <?= $open ? 'show' : '' ?>" id="grp-<?= e($entry['id']) ?>">
                <div class="nav-sub">
                    <?php foreach ($entry['items'] as [$label, $path, $icon]): ?>
                        <a href="<?= url($path) ?>" class="nav-link-item <?= $activePath === $path ? 'active' : '' ?>">
                            <span class="nav-sub-icon"><i class="bi bi-<?= e($icon ?? 'circle') ?>" aria-hidden="true"></i></span>
                            <?= e($label) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>
