<?php
/** Process 2.0 Record Agricultural Resources. Stub: replace each mockup() call with the real view and logic. */
class OrganizationController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Organizations',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Associations and cooperatives list',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Organization',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'todo'    => [
                'Organization details form',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Organization Details',
            'group'   => 'Agricultural Resources',
            'process' => '2.0 Record Agricultural Resources',
            'status'  => 'mockup',
            'id'      => $id,
            'todo'    => [
                'Officers and member list',
                'Add members from D1 profiles',
            ],
        ]);
    }
}
