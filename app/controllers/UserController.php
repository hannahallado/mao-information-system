<?php
/** Process System Management. Stub: replace each mockup() call with the real view and logic. */
class UserController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'User Management',
            'group'   => 'System Management',
            'process' => 'System Management',
            'status'  => 'mockup',
            'todo'    => [
                'User list, roles and sectors',
                'Activate / deactivate accounts',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add User',
            'group'   => 'System Management',
            'process' => 'System Management',
            'status'  => 'mockup',
            'todo'    => [
                'Name, username, role, sector, password',
            ],
        ]);
    }
}
