<?php
/** Process 2.0 Record Agricultural Resources. Stub: replace each mockup() call with the real view and logic. */
class LivestockController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Livestock and Poultry',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'List by species and owner',
                'Type, quantity, production details',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Livestock/Poultry Record',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Form with owner/profile association',
            ],
        ]);
    }
}
