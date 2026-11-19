<?php
/**
 * Report catalogue for the Reports Center (process 6.0).
 * Each report: slug => [title, functional?]. Functional = part of the 50% build.
 * All reports share one reusable report-view template.
 */
return [
    'farmer-resource' => ['title' => 'Farmer and Resource Reports', 'reports' => [
        'masterlist'      => ['Farmer/Fisherfolk Masterlist', true],
        'equipment'       => ['Equipment Inventory', false],
        'organizations'   => ['Organization Report', false],
        'livestock'       => ['Livestock and Poultry Report', false],
        'fisheries-boat'  => ['Fisheries and Boat Report', false],
    ]],
    'production-monitoring' => ['title' => 'Production and Monitoring Reports', 'reports' => [
        'production'      => ['Agricultural Production Report', false],
        'training'        => ['Training and Technical Assistance Report', false],
        'damage-pest'     => ['Agricultural Damage and Pest Report', false],
    ]],
    'programs-assistance' => ['title' => 'Programs and Assistance Reports', 'reports' => [
        'programs-beneficiaries' => ['Programs and Beneficiary Report', false],
        'assistance-history'     => ['Beneficiary Assistance History', true],
        'program-accomplishment' => ['Program Accomplishment Report', false],
    ]],
    'summary' => ['title' => 'Summary Reports', 'reports' => [
        'barangay-commodity-summary' => ['Barangay/Commodity Summary Report', false],
    ]],
];
