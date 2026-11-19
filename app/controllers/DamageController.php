<?php
/** Process 4.0 Monitor Agricultural Activities. Stub: replace each mockup() call with the real view and logic. */
class DamageController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Damage and Pest Monitoring',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Damage/pest record list',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Record Damage/Pest',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Affected farmer/farm, date, type, area, severity, calamity name',
                'Response/action notes',
            ],
        ]);
    }
}
