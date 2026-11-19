<?php
/** Process 2.0 Record Agricultural Resources. Stub: replace each mockup() call with the real view and logic. */
class EquipmentController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Equipment Inventory',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Equipment and machinery list',
                'Type, quantity, condition, ownership/location, status, agency, year granted',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Equipment',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Form: assigned to an organization OR a farmer, not both',
            ],
        ]);
    }
}
