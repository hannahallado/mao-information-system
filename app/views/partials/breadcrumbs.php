<?php if (!empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <?php $last = count($breadcrumbs) - 1; ?>
        <?php foreach ($breadcrumbs as $i => [$label, $path]): ?>
            <?php if ($i === $last || $path === null): ?>
                <li class="breadcrumb-item <?= $i === $last ? 'active' : '' ?>"><?= e($label) ?></li>
            <?php else: ?>
                <li class="breadcrumb-item"><a href="<?= url($path) ?>"><?= e($label) ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
