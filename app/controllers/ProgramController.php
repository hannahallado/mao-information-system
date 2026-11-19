<?php
/** Process 5.0 Administer Agricultural Programs. Stub: replace each mockup() call with the real view and logic. */
class ProgramController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Programs and Projects',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'todo'    => [
                'Program list with status and key dates',
                'Create / view / edit actions',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Create Program',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'todo'    => [
                'Name, description, objectives, schedule, source agency, target beneficiaries, status',
                'Assign Program ID with IdGenerator::programId()',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Program Details',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'id'      => $id,
            'todo'    => [
                'Tabs: Overview (status, target vs actual progress) | Beneficiaries | Distributions',
                'Beneficiaries tab: master list + "Add from registered profiles" picker (no new farmer records)',
            ],
        ]);
    }

    public function edit(string $id): void
    {
        $this->mockup([
            'title'   => 'Edit Program',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'id'      => $id,
            'todo'    => [
                'Prefilled program form',
            ],
        ]);
    }
}
