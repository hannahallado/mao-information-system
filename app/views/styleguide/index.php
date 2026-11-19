<div class="page-head">
    <div>
        <h1>Farmer/Fisherfolk List</h1>
        <p class="page-sub">UI kit: sample screen using the theme components (list page pattern)</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-outline-secondary btn-sm">Export</button>
        <button class="btn btn-primary btn-sm">+ Register Farmer</button>
    </div>
</div>

<!-- stat row -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ([['FF','Farmers','1,284'],['FS','Fisherfolk','312'],['AR','Farm area (ha)','4,905.5'],['PR','Active programs','6']] as [$i,$l,$v]): ?>
                <div class="col-6 col-lg-3">
                    <div class="stat"><span class="stat-icon"><?= e($i) ?></span>
                        <div><div class="stat-label"><?= e($l) ?></div><div class="stat-value"><?= e($v) ?></div></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- filter bar + table -->
<div class="filter-bar">
    <input class="form-control" placeholder="Search name, ID or RSBSA no.">
    <select class="form-select"><option>All barangays</option></select>
    <select class="form-select"><option>All livelihood types</option></select>
    <select class="form-select"><option>Active</option></select>
</div>

<div class="table-wrap mb-3">
    <div class="table-scroll">
        <table class="table table-hover">
            <thead><tr><th>Farmer ID</th><th>Name</th><th>Barangay</th><th>Livelihood</th><th>Status</th><th class="num">Farm area (ha)</th><th></th></tr></thead>
            <tbody>
                <?php foreach ([
                    ['FRM-2026-00001','Dela Cruz, Juan P.','Brgy. Alijis','Farmer','Active','badge-soft-success',2.50],
                    ['FRM-2026-00002','Santos, Maria L.','Brgy. Granada','Fisherfolk','Active','badge-soft-success',0.00],
                    ['FRM-2026-00003','Reyes, Pedro A.','Brgy. Mandalagan','Both','Transferred','badge-soft-warning',1.25],
                    ['FRM-2026-00004','Garcia, Ana M.','Brgy. Taculing','Farmer','Inactive','badge-soft-muted',3.10],
                ] as [$id,$n,$b,$lv,$st,$cls,$ha]): ?>
                    <tr>
                        <td><code><?= e($id) ?></code></td><td class="fw-semibold"><?= e($n) ?></td><td><?= e($b) ?></td><td><?= e($lv) ?></td>
                        <td><span class="badge <?= $cls ?>"><?= e($st) ?></span></td>
                        <td class="num"><?= number_format($ha, 2) ?></td>
                        <td class="table-actions"><button class="btn btn-outline-secondary btn-sm">View</button> <button class="btn btn-outline-secondary btn-sm">Edit</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Farmer Profile
                <ul class="nav nav-segmented"><li class="nav-item"><a class="nav-link active" href="#">Overview</a></li><li class="nav-item"><a class="nav-link" href="#">Assistance</a></li><li class="nav-item"><a class="nav-link" href="#">Training</a></li></ul>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Last name</label><input class="form-control" value="Dela Cruz"></div>
                    <div class="col-md-6"><label class="form-label">First name</label><input class="form-control" value="Juan"></div>
                    <div class="col-md-6"><label class="form-label">RSBSA no.</label><input class="form-control is-invalid" value="06-45-12-001"><div class="invalid-feedback">RSBSA number already exists.</div></div>
                    <div class="col-md-6"><label class="form-label">Barangay</label><select class="form-select"><option>Brgy. Alijis</option></select></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <button class="btn btn-outline-secondary btn-sm">Cancel</button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#dupModal">Save</button>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card mb-3"><div class="card-body">
            <h6 class="card-title">Assistance History</h6>
            <ul class="timeline mb-0">
                <li><div class="fw-semibold">Rice Seed Distribution 2026</div><div class="text-muted small">Certified seeds &middot; 2 bags &middot; Mar 14, 2026</div></li>
                <li><div class="fw-semibold">Fertilizer Subsidy</div><div class="text-muted small">Urea &middot; 5 bags &middot; Jan 22, 2026</div></li>
            </ul>
        </div></div>
        <div class="alert alert-warning mb-2"><strong>Possible duplicate found.</strong> A similar profile already exists.</div>
        <div class="alert alert-success mb-0">Record saved successfully.</div>
    </div>
</div>

<!-- warning modal pattern -->
<div class="modal fade" id="dupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Possible Duplicate Found</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p class="mb-2">This farmer already received <strong>Seeds</strong> in March. An override reason is required.</p>
            <label class="form-label">Override reason</label><textarea class="form-control"></textarea>
        </div>
        <div class="modal-footer"><button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary btn-sm">Override and save</button></div>
    </div></div>
</div>
