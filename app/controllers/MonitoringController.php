<?php
/** Process 4.0 Monitor Agricultural Activities. Stub: replace each mockup() call with the real view and logic. */
class MonitoringController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Monitoring Overview',
            'group'   => 'Agricultural Monitoring',
            'process' => '4.0 Monitor Agricultural Activities',
            'status'  => 'mockup',
            'todo'    => [
                'Summary of recorded activities',
                'Filters (date, barangay, activity type, commodity)',
                'Links to production, damage/pest and training',
            ],
        ]);
    }
}
