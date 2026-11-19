<?php
/** Process 2.0 Record Agricultural Resources. Stub: replace each mockup() call with the real view and logic. */
class FisheryController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Fishery Records',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Searchable fishery list',
                'Fishery type, operating area, related details',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Fishery Record',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Fishery form linked to a profile where applicable',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Fishery Details',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'id'      => $id,
            'todo'    => [
                'Details and profile link',
                'Edit action',
            ],
        ]);
    }
}
