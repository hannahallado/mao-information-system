<div class="page-head"><h1>Reports Center</h1></div>
<div class="row g-3">
    <?php foreach ($groups as $gslug => $group): ?>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <a href="<?= url('/reports/group/' . $gslug) ?>" style="color: inherit;"><?= e($group['title']) ?></a>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($group['reports'] as $slug => [$rtitle, $functional]): ?>
                        <a href="<?= url('/reports/' . $slug) ?>" class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom report-row">
                            <span><?= e($rtitle) ?></span>
                            <span class="badge <?= $functional ? 'badge-soft-success' : 'badge-soft-muted' ?>"><?= $functional ? 'Functional' : 'Mockup' ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
