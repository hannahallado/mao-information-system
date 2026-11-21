<?php
/**
 * Sidebar definition (from the final screen checklist).
 * 'link'  => single item        ['type','label','path']
 * 'section' => optional small heading printed above the entry (Main / Processes / Other)
 * 'group' => collapsible group  ['type','id','tag','label','items' => [[label, path], ...], 'roles'?]
 * Forms, edit and detail pages are NOT listed here; they open from list screens.
 */
return [
    ['type' => 'link', 'label' => 'Dashboard', 'path' => '/home', 'icon' => 'grid-1x2'],

    ['type' => 'group', 'id' => 'p1', 'tag' => '1.0', 'label' => 'Farmer/Fisherfolk Registry', 'icon' => 'people', 'items' => [
        ['Farmer/Fisherfolk List', '/farmers', 'person-lines-fill'],
        ['Register Farmer/Fisherfolk', '/farmers/create', 'person-plus'],
    ]],

    ['type' => 'group', 'id' => 'p2', 'tag' => '2.0', 'label' => 'Agricultural Resources', 'icon' => 'basket', 'items' => [
        ['Farm Records', '/farms', 'tree'],
        ['Fishery Records', '/fishery', 'water'],
        ['Livestock and Poultry', '/livestock', 'egg'],
        ['Equipment Inventory', '/equipment', 'tools'],
        ['Organizations', '/organizations', 'building'],
    ]],

    ['type' => 'group', 'id' => 'p3', 'tag' => '3.0', 'label' => 'GIS Mapping', 'icon' => 'geo-alt', 'items' => [
        ['Map Overview', '/map', 'map'],
        ['Farm Locations', '/map/farms', 'geo'],
        ['Fishery Locations', '/map/fishery', 'pin-map'],
    ]],

    ['type' => 'group', 'id' => 'p4', 'tag' => '4.0', 'label' => 'Agricultural Monitoring', 'icon' => 'activity', 'items' => [
        ['Monitoring Overview', '/monitoring', 'speedometer2'],
        ['Production Monitoring', '/monitoring/production', 'graph-up-arrow'],
        ['Damage and Pest Monitoring', '/monitoring/damage', 'bug'],
        ['Training and Activities', '/monitoring/training', 'calendar-event'],
    ]],

    ['type' => 'group', 'id' => 'p5', 'tag' => '5.0', 'label' => 'Programs and Assistance', 'icon' => 'hand-thumbs-up', 'items' => [
        ['Programs and Projects', '/programs', 'clipboard-data'],          // detail page has tabs: Overview | Beneficiaries | Distributions
        ['Assistance Distribution', '/assistance', 'box-seam'],
        ['Assistance History', '/assistance/history', 'clock-history'],
    ]],

    ['type' => 'group', 'id' => 'p6', 'tag' => '6.0', 'label' => 'Reports', 'icon' => 'bar-chart-line', 'items' => [
        ['Farmer and Resource Reports', '/reports/group/farmer-resource', 'file-earmark-bar-graph'],
        ['Production and Monitoring Reports', '/reports/group/production-monitoring', 'graph-up'],
        ['Programs and Assistance Reports', '/reports/group/programs-assistance', 'file-earmark-text'],
        ['Summary Reports', '/reports/group/summary', 'clipboard2-check'],
    ]],

    ['type' => 'group', 'section' => 'Other', 'id' => 'sys', 'tag' => '', 'label' => 'System Management', 'icon' => 'gear', 'roles' => ['agriculturist'], 'items' => [
        ['User Management', '/system/users', 'people'],
        ['Access Control', '/system/access-control', 'shield-lock'],
        ['Backup and Security', '/system/backup', 'database-down'],
        ['System Settings', '/system/settings', 'sliders'],
    ]],
];
