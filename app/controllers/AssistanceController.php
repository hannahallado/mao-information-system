<?php
/** Process 5.0 Administer Agricultural Programs. Stub: replace each mockup() call with the real view and logic. */
class AssistanceController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Assistance Distribution',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'todo'    => [
                'Distribution list with filters (program, barangay, date, flagged duplicates only)',
                '"Record Distribution" button',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Record Distribution',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'todo'    => [
                'Program, beneficiary (Farmer ID), assistance type, quantity, unit, date, releasing personnel',
                'Duplicate assistance warning listing earlier assistance',
                'Override reason required and kept',
                'Confirm before saving; assign IdGenerator::assistanceId()',
            ],
        ]);
    }

    public function history(): void
    {
        $this->mockup([
            'title'   => 'Assistance History',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'todo'    => [
                'Search a farmer to see the timeline, newest first',
                'Toggle: by farmer / by program',
            ],
        ]);
    }

    public function farmerHistory(string $id): void
    {
        $this->mockup([
            'title'   => 'Farmer Assistance History',
            'group'   => 'Programs and Assistance',
            'process' => '5.0 Administer Agricultural Programs',
            'status'  => 'functional',
            'id'      => $id,
            'todo'    => [
                'Same timeline, opened from the Farmer Profile tab',
            ],
        ]);
    }
}
