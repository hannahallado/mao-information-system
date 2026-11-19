<?php
/** Process 2.0 Record Agricultural Resources. Stub: replace each mockup() call with the real view and logic. */
class FarmController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Farm Records',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Searchable farm list',
                'Link each farm to a Farmer ID',
                'Area, location reference, land use, commodities',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Farm',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Farm form with farmer picker (from D1 profiles)',
                'Area and ecosystem fields',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Farm Details',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'id'      => $id,
            'todo'    => [
                'Farm details and owner profile link',
                'Edit action',
            ],
        ]);
    }
}
