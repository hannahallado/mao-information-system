<?= Ui::pageHead('Edit Profile', Profile::fullName($profile),
    '<a class="btn btn-outline-secondary btn-sm" href="' . e(url('/farmers/' . $profile['id'])) . '">Back to profile</a>') ?>

<form method="post" action="<?= url('/farmers/' . $profile['id']) ?>" data-duplicate-check="<?= url('/farmers/check-duplicate') ?>" novalidate>
    <?= Csrf::field() ?>
    <input type="hidden" name="exclude_id" value="<?= e($profile['id']) ?>">
    <?= partial('farmers/_form', ['profile' => $profile, 'mode' => 'edit', 'barangays' => $barangays]) ?>
    <div class="form-actions">
        <a class="btn btn-outline-secondary" href="<?= url('/farmers/' . $profile['id']) ?>">Cancel</a>
        <button class="btn btn-primary" type="submit">Save changes</button>
    </div>
</form>

<?= partial('duplicate-modal') ?>
<?php if (!empty($duplicatePayload)): ?>
<script>window.__duplicatePayload = <?= json_encode($duplicatePayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<?php endif; ?>
