<?php $isFunctional = ($status === 'functional'); ?>
<div class="page-head">
    <div>
        <h1><?= e($title) ?></h1>
        <?php if (!empty($process)): ?><p class="page-sub">Process <?= e($process) ?></p><?php endif; ?>
    </div>
    <div class="page-actions">
        <span class="badge <?= $isFunctional ? 'badge-soft-success' : 'badge-soft-muted' ?>">
            <?= $isFunctional ? 'Functional (50% scope)' : 'Mockup' ?>
        </span>
    </div>
</div>

<?php if (!empty($id)): ?>
    <p><span class="text-muted">Record:</span> <code><?= e($id) ?></code></p>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h6 class="card-title">Screen checklist (replace this placeholder)</h6>
        <?php if ($todo): ?>
            <ul class="mb-0 ps-3">
                <?php foreach ($todo as $item): ?><li class="mb-1"><?= e($item) ?></li><?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="mb-0 text-muted">No items listed.</p>
        <?php endif; ?>
    </div>
</div>
