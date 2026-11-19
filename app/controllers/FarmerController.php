<?php
/** Process 1.0 Register Farmers/Fisherfolk. Stub: replace each mockup() call with the real view and logic. */
class FarmerController extends Controller
{
    public function index(): void
    {
        $this->mockup([
            'title'   => 'Farmer/Fisherfolk List',
            'group'   => 'Farmer/Fisherfolk Registry',
            'process' => '1.0 Register Farmers/Fisherfolk',
            'status'  => 'functional',
            'todo'    => [
                'Searchable, filterable table (Barangay, Livelihood Type, Status)',
                'Show unique Farmer/Fisherfolk ID and essential fields',
                'Actions: view profile, edit, archive, restore',
                'Empty, loading and no-results states',
            ],
        ]);
    }

    public function create(): void
    {
        $this->mockup([
            'title'   => 'Register Farmer/Fisherfolk',
            'group'   => 'Farmer/Fisherfolk Registry',
            'process' => '1.0 Register Farmers/Fisherfolk',
            'status'  => 'functional',
            'todo'    => [
                'Profile, contact, address/barangay and classification fields with validation',
                'Duplicate profile warning modal (Use existing / Register anyway + reason)',
                'Assign unique ID with IdGenerator::farmerId()',
                'Save / Cancel with clear feedback',
            ],
        ]);
    }

    public function show(string $id): void
    {
        $this->mockup([
            'title'   => 'Farmer/Fisherfolk Profile',
            'group'   => 'Farmer/Fisherfolk Registry',
            'process' => '1.0 Register Farmers/Fisherfolk',
            'status'  => 'functional',
            'id'      => $id,
            'todo'    => [
                'Profile details with related records (farms, fishery, livestock, organizations, training)',
                'Assistance History tab, linked through the shared Farmer ID (see AssistanceController::farmerHistory)',
                'Edit, archive and restore actions',
            ],
        ]);
    }

    public function edit(string $id): void
    {
        $this->mockup([
            'title'   => 'Edit Profile',
            'group'   => 'Farmer/Fisherfolk Registry',
            'process' => '1.0 Register Farmers/Fisherfolk',
            'status'  => 'functional',
            'id'      => $id,
            'todo'    => [
                'Prefilled form with validation',
                'Duplicate check ignoring the current record',
            ],
        ]);
    }
}
