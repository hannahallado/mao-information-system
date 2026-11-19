<?php
/** Process 4.0 Monitor Agricultural Activities. Stub: replace each mockup() call with the real view and logic. */
class ProductionController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Production Monitoring',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Searchable production list',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Record Production',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Farmer/farm, commodity, period (1st/2nd half), area, volume, unit',
                'Validate numbers, dates and references',
            ],
        ]);
    }
}
