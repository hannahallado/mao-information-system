<?= Ui::pageHead('Register Profile', 'Create one profile per person.',
    '<a class="btn btn-outline-secondary btn-sm" href="' . e(url('/farmers')) . '">Back to list</a>') ?>

<form method="post" action="<?= url('/farmers') ?>" data-duplicate-check="<?= url('/farmers/check-duplicate') ?>" novalidate>
    <?= Csrf::field() ?>
    <?= partial('farmers/_form', ['profile' => $profile, 'mode' => 'create', 'barangays' => $barangays]) ?>
    <div class="form-actions">
        <a class="btn btn-outline-secondary" href="<?= url('/farmers') ?>">Cancel</a>
        <button class="btn btn-primary" type="submit">Register</button>
    </div>
</form>

<?= partial('duplicate-modal') ?>
<?php if (!empty($duplicatePayload)): ?>
<script>window.__duplicatePayload = <?= json_encode($duplicatePayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<?php endif; ?>
