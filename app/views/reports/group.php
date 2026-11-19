<div class="page-head"><h1><?= e($group['title']) ?></h1></div>
<div class="card">
    <?php foreach ($group['reports'] as $slug => [$rtitle, $functional]): ?>
        <a href="<?= url('/reports/' . $slug) ?>" class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom report-row">
            <span><?= e($rtitle) ?></span>
            <span class="badge <?= $functional ? 'badge-soft-success' : 'badge-soft-muted' ?>"><?= $functional ? 'Functional' : 'Mockup' ?></span>
        </a>
    <?php endforeach; ?>
</div>
