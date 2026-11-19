<?php
/** Process 3.0 Map Farm and Fishery Locations (GIS). Stub: replace each mockup() call with the real view and logic. */
class MapController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Map Overview',
            'group'   => 'GIS Mapping',
            'process' => '3.0 Map Farm and Fishery Locations (GIS)',
            'status'  => 'mockup',
            'todo'    => [
                'Leaflet map with farm and fishery markers/boundaries',
                'Layer toggles and filters (barangay, commodity, category)',
                'Detail panel linking to the D1 profile and D2 record',
                'Clear state when coordinates are unavailable',
            ],
        ]);
    }

    public function farms(): void
    {
        $this->mockup([
            'title'   => 'Farm Locations',
            'group'   => 'GIS Mapping',
            'process' => '3.0 Map Farm and Fishery Locations (GIS)',
            'status'  => 'mockup',
            'todo'    => [
                'Farm layer preset of the map',
            ],
        ]);
    }

    public function fishery(): void
    {
        $this->mockup([
            'title'   => 'Fishery Locations',
            'group'   => 'GIS Mapping',
            'process' => '3.0 Map Farm and Fishery Locations (GIS)',
            'status'  => 'mockup',
            'todo'    => [
                'Fishery layer preset of the map',
            ],
        ]);
    }
}
