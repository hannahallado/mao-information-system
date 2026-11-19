<?php
$u        = Auth::user();
$name     = $u['full_name'] ?? '';
$initials = strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($name)) ?: [], 0, 2))));
$roleName = Auth::isAgriculturist() ? 'Municipal Agriculturist' : 'MAO Technician';
?>
<header class="app-header">
    <div class="user-chip">
        <span class="avatar"><?= e($initials) ?></span>
        <div>
            <div class="user-name"><?= e($name) ?></div>
            <div class="user-sub"><?= e($roleName) ?></div>
        </div>
    </div>
    <form method="post" action="<?= url('/logout') ?>" class="m-0">
        <?= Csrf::field() ?>
        <button class="btn btn-primary btn-sm">Log out</button>
    </form>
</header>
