<?php
/** Process 4.0 Monitor Agricultural Activities. Stub: replace each mockup() call with the real view and logic. */
class TrainingController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Training and Activities',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Trainings, seminars, technical assistance list',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Add Training/Activity',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Title/type, date, venue/barangay, topic, personnel',
                'Participants linked to D1 profiles',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Activity Details',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'id'      => $id,
            'todo'    => [
                'Details and participant list',
            ],
        ]);
    }
}
