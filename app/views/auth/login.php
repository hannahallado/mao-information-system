<div class="auth-card">
    <div class="auth-brand">
        <span class="brand-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 4 13c0-5 4-9 16-9 0 12-4 16-9 16z"/><path d="M4 21c3-6 6-9 11-11"/></svg>
        </span>
        <div>
            <div class="brand-name"><?= e(config('app_name')) ?></div>
            <div class="brand-sub">Municipal Agriculture Office</div>
        </div>
    </div>

    <form method="post" action="<?= url('/login') ?>" novalidate>
        <?= Csrf::field() ?>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username"
                   class="form-control <?= errors('username') ? 'is-invalid' : '' ?>"
                   value="<?= e(old('username')) ?>" autofocus>
            <div class="invalid-feedback"><?= e(errors('username')) ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password"
                   class="form-control <?= errors('password') ? 'is-invalid' : '' ?>">
            <div class="invalid-feedback"><?= e(errors('password')) ?></div>
        </div>
        <button class="btn btn-primary w-100">Log in</button>
    </form>
</div>
