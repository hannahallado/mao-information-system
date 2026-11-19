<div class="page-head">
    <div>
        <h1>Welcome back, <?= e(explode(' ', trim($user['full_name']))[0]) ?></h1>
        <p class="page-sub">Choose a process to begin.</p>
    </div>
</div>

<div class="row g-3">
    <?php foreach ($tiles as [$tag, $label, $path, $functional]): ?>
        <div class="col-md-6 col-xl-4">
            <a href="<?= url($path) ?>" class="card card-hover h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-icon"><?= e($tag) ?></span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="color: var(--c-primary);"><?= e($label) ?></div>
                        <span class="badge mt-1 <?= $functional ? 'badge-soft-success' : 'badge-soft-muted' ?>">
                            <?= $functional ? 'Functional' : 'Mockup' ?>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
