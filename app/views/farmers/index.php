<?php
$hasFilters = array_filter($filters, fn ($v) => $v !== '') !== [];

$columns = [
    ['label' => 'Name', 'render' => fn ($r) => '<a class="fw-semibold" href="' . e(url('/farmers/' . $r['id'])) . '">' . e(Farmer::fullName($r)) . '</a>'],
    ['label' => 'Barangay', 'key' => 'barangay_name'],
    ['label' => 'Livelihood', 'render' => fn ($r) => livelihoodBadges($r)],
    ['label' => 'RSBSA / FishR', 'render' => fn ($r) => registryNumbers($r)],
    ['label' => 'Contact', 'key' => 'contact_no'],
    ['label' => 'Status', 'render' => fn ($r) => Ui::statusBadge($r['status'], Profile::STATUSES[$r['status']] ?? null)],
    ['label' => '', 'class' => 'table-actions', 'render' => fn ($r) =>
        '<a class="btn btn-outline-secondary btn-sm" href="' . e(url('/farmers/' . $r['id'])) . '">View</a> '
      . '<a class="btn btn-outline-secondary btn-sm" href="' . e(url('/farmers/' . $r['id'] . '/edit')) . '">Edit</a>'],
];

$registerBtn = '<a class="btn btn-primary btn-sm" href="' . e(url('/farmers/create')) . '">+ Register Profile</a>';

$empty = $hasFilters
    ? Ui::emptyState('No matching profiles', 'Try a different name or number, or clear the filters.',
        '<a class="btn btn-outline-secondary btn-sm" href="' . e(url('/farmers')) . '">Clear filters</a>')
    : Ui::emptyState('No profiles registered yet', 'Registered profiles will appear here.', $registerBtn);
?>
<?= Ui::pageHead('Farmer/Fisherfolk Registry', 'Search, filter and open registered profiles.', $registerBtn) ?>

<?= Ui::filterBar('/farmers', [
    ['type' => 'search', 'name' => 'q', 'placeholder' => 'Search name, RSBSA/FishR no. or contact'],
    ['name' => 'barangay_id', 'all' => 'All barangays', 'options' => $barangays],
    ['name' => 'livelihood_type', 'all' => 'All livelihoods', 'options' => Farmer::LIVELIHOODS],
    ['name' => 'status', 'all' => 'All statuses', 'options' => Profile::STATUSES],
], $filters) ?>

<?= Ui::dataTable($columns, $result['data'], ['empty' => $empty]) ?>
<?= Ui::pagination($result, '/farmers', array_filter($filters, fn ($v) => $v !== '')) ?>

<?php
function livelihoodBadges($r) {
    $badges = [];
    if ($r['has_farming']) {
        $badges[] = '<span class="badge badge-soft-success">Farmer</span>';
    }
    if ($r['has_fishery']) {
        $badges[] = '<span class="badge badge-soft-success">Fisherfolk</span>';
    }
    return implode(' ', $badges) ?: '<span class="text-muted">—</span>';
}

function registryNumbers($r) {
    $nums = [];
    if ($r['farming_rsbsa']) {
        $nums[] = '<code>' . e($r['farming_rsbsa']) . '</code>';
    }
    if ($r['fishery_rsbsa']) {
        $nums[] = '<code>' . e($r['fishery_rsbsa']) . '</code>';
    }
    if ($r['fishr_no']) {
        $nums[] = '<code>' . e($r['fishr_no']) . '</code>';
    }
    return implode(' ', $nums) ?: '<span class="text-muted">—</span>';
}
?>
