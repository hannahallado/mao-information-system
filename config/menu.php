<?php
/**
 * Sidebar definition (from the final screen checklist).
 * 'link'  => single item        ['type','label','path']
 * 'section' => optional small heading printed above the entry (Main / Processes / Other)
 * 'group' => collapsible group  ['type','id','tag','label','items' => [[label, path], ...], 'roles'?]
 * Forms, edit and detail pages are NOT listed here; they open from list screens.
 */
return [
    ['type' => 'link', 'label' => 'Dashboard', 'path' => '/home'],

    ['type' => 'group', 'id' => 'p1', 'tag' => '1.0', 'label' => 'Farmer/Fisherfolk Registry', 'items' => [
        ['Farmer/Fisherfolk List', '/farmers'],
        ['Register Farmer/Fisherfolk', '/farmers/create'],
    ]],

    ['type' => 'group', 'id' => 'p2', 'tag' => '2.0', 'label' => 'Agricultural Resources', 'items' => [
        ['Farm Records', '/farms'],
        ['Fishery Records', '/fishery'],
        ['Livestock and Poultry', '/livestock'],
        ['Equipment Inventory', '/equipment'],
        ['Organizations', '/organizations'],
    ]],

    ['type' => 'group', 'id' => 'p3', 'tag' => '3.0', 'label' => 'GIS Mapping', 'items' => [
        ['Map Overview', '/map'],
        ['Farm Locations', '/map/farms'],
        ['Fishery Locations', '/map/fishery'],
    ]],

    ['type' => 'group', 'id' => 'p4', 'tag' => '4.0', 'label' => 'Agricultural Monitoring', 'items' => [
        ['Monitoring Overview', '/monitoring'],
        ['Production Monitoring', '/monitoring/production'],
        ['Damage and Pest Monitoring', '/monitoring/damage'],
        ['Training and Activities', '/monitoring/training'],
    ]],

    ['type' => 'group', 'id' => 'p5', 'tag' => '5.0', 'label' => 'Programs and Assistance', 'items' => [
        ['Programs and Projects', '/programs'],          // detail page has tabs: Overview | Beneficiaries | Distributions
        ['Assistance Distribution', '/assistance'],
        ['Assistance History', '/assistance/history'],
    ]],

    ['type' => 'group', 'id' => 'p6', 'tag' => '6.0', 'label' => 'Reports', 'items' => [
        ['Farmer and Resource Reports', '/reports/group/farmer-resource'],
        ['Production and Monitoring Reports', '/reports/group/production-monitoring'],
        ['Programs and Assistance Reports', '/reports/group/programs-assistance'],
        ['Summary Reports', '/reports/group/summary'],
    ]],

    ['type' => 'group', 'section' => 'Other', 'id' => 'sys', 'tag' => '', 'label' => 'System Management', 'roles' => ['agriculturist'], 'items' => [
        ['User Management', '/system/users'],
        ['Access Control', '/system/access-control'],
        ['Backup and Security', '/system/backup'],
        ['System Settings', '/system/settings'],
    ]],
];
