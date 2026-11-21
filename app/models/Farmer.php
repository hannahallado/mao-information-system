<?php
/**
 * D1 Farmer/Fisherfolk Profiles (compatibility layer).
 * 
 * This is a facade for the new separated models (Profile, FarmingProfile, FisheryProfile).
 * Old code referencing "Farmer" continues to work while we transition to the new architecture.
 */
class Farmer extends Model
{
    protected static string $table = 'profiles';  // Use profiles table now
    protected static bool $softDeletes = true;
    protected static bool $trackCreator = true;

    public const LIVELIHOODS = ['farmer' => 'Farmer', 'fisherfolk' => 'Fisherfolk', 'both' => 'Farmer and Fisherfolk'];
    public const STATUSES    = ['active' => 'Active', 'inactive' => 'Inactive', 'transferred' => 'Transferred', 'deceased' => 'Deceased'];
    public const SEXES       = ['Male' => 'Male', 'Female' => 'Female'];

    /** Delegate to Profile */
    public static function makeNameKey(string $last, string $first): string
    {
        return Profile::makeNameKey($last, $first);
    }

    /** Delegate to Profile */
    public static function fullName(array $f): string
    {
        return Profile::fullName($f);
    }

    /** Compatibility: get profile with livelihoods as a flattened farmer row */
    public static function withBarangay(int|string $id): ?array
    {
        $profile = Profile::withBarangay($id);
        if (!$profile) {
            return null;
        }

        $livelihoods = Profile::getLivelihoods((int) $profile['id']);
        $farming = $livelihoods['farming'];
        $fishery = $livelihoods['fishery'];

        // Return flattened structure for backward compatibility
        return $profile + [
            'farmer_no'      => null,  // No longer used; livelihoods have their own IDs
            'rsbsa_no'       => $farming['rsbsa_no'] ?? ($fishery['rsbsa_no'] ?? null),
            'fishr_no'       => $fishery['fishr_no'] ?? null,
            'livelihood_type' => $this->getLivelihoodType($farming, $fishery),
        ];
    }

    /** Compatibility: search across profiles and livelihoods */
    public static function search(array $filters, int $page = 1, ?int $perPage = null): array
    {
        return Profile::search($filters, $page, $perPage);
    }

    /** Compatibility: insert creates a profile + optional livelihoods */
    public static function insert(array $data): int
    {
        $profileData = [
            'last_name'    => $data['last_name'],
            'first_name'   => $data['first_name'],
            'middle_name'  => $data['middle_name'] ?? null,
            'suffix'       => $data['suffix'] ?? null,
            'sex'          => $data['sex'],
            'birthdate'    => $data['birthdate'] ?? null,
            'contact_no'   => $data['contact_no'] ?? null,
            'barangay_id'  => $data['barangay_id'],
            'address_detail' => $data['address_detail'] ?? null,
            'status'       => $data['status'] ?? 'active',
            'name_key'     => $data['name_key'],
        ];

        $profileId = Profile::insert($profileData);

        // Create livelihoods based on livelihood_type
        $type = $data['livelihood_type'] ?? 'farmer';
        if (in_array($type, ['farmer', 'both'])) {
            FarmingProfile::insert([
                'id'       => $profileId,
                'rsbsa_no' => $data['rsbsa_no'] ?? null,
                'status'   => $data['status'] ?? 'active',
            ]);
        }
        if (in_array($type, ['fisherfolk', 'both'])) {
            FisheryProfile::insert([
                'id'       => $profileId,
                'rsbsa_no' => $data['rsbsa_no'] ?? null,
                'fishr_no' => $data['fishr_no'] ?? null,
                'status'   => $data['status'] ?? 'active',
            ]);
        }

        return $profileId;
    }

    /** Compatibility: update profile and livelihoods. Returns bool to match Model signature. */
    public static function update(int|string $id, array $data): bool
    {
        $profileData = [];
        $profileCols = ['last_name', 'first_name', 'middle_name', 'suffix', 'sex', 'birthdate',
                       'contact_no', 'barangay_id', 'address_detail', 'status', 'name_key'];
        foreach ($profileCols as $col) {
            if (array_key_exists($col, $data)) {
                $profileData[$col] = $data[$col];
            }
        }

        if ($profileData) {
            return Profile::update((int) $id, $profileData);
        }
        return true;
    }

    private static function getLivelihoodType(?array $farming, ?array $fishery): string
    {
        $hasFarming = $farming !== null;
        $hasFishery = $fishery !== null;
        if ($hasFarming && $hasFishery) {
            return 'both';
        } elseif ($hasFishery) {
            return 'fisherfolk';
        }
        return 'farmer';
    }

    /**
     * Static method to determine livelihood type from boolean flags.
     * Used in search results and reporting.
     */
    public static function livelihoodTypeFromFlags(bool $hasFarming, bool $hasFishery): string
    {
        if ($hasFarming && $hasFishery) {
            return 'both';
        } elseif ($hasFishery) {
            return 'fisherfolk';
        }
        return 'farmer';
    }
}
