<?php
/** Fishery livelihood profile. */
class FisheryProfile extends Model
{
    protected static string $table = 'fishery_profiles';
    protected static bool $softDeletes = true;
    protected static bool $trackCreator = true;
    protected static array $fillable = [
        'id', 'rsbsa_no', 'fishr_no', 'status', 'created_by', 'created_at', 'updated_at', 'deleted_at'
    ];

    public const STATUSES = ['active' => 'Active', 'inactive' => 'Inactive', 'transferred' => 'Transferred', 'deceased' => 'Deceased'];

    /**
     * Get the associated profile.
     */
    public static function withProfile(int $id): ?array
    {
        return self::rawOne(
            'SELECT fsp.*, p.last_name, p.first_name, p.middle_name, p.suffix, p.sex, p.birthdate,
                    p.contact_no, p.barangay_id, p.address_detail, p.status AS profile_status,
                    b.name AS barangay_name
             FROM fishery_profiles fsp
             JOIN profiles p ON p.id = fsp.id
             LEFT JOIN barangays b ON b.id = p.barangay_id
             WHERE fsp.id = ? AND fsp.deleted_at IS NULL LIMIT 1',
            [$id]
        );
    }

    /**
     * Create a fishery profile for an existing profile (custom insert to handle id as FK).
     */
    public static function insert(array $data): int
    {
        $cols = ['id', 'rsbsa_no', 'fishr_no', 'status'];
        $vals = [];
        $params = [];

        foreach ($cols as $col) {
            if (isset($data[$col])) {
                $vals[] = '?';
                $params[] = $data[$col];
            }
        }

        if (empty($params)) {
            throw new Exception('No data provided for fishery profile');
        }

        $colList = implode(', ', array_map(fn ($c) => "`$c`", array_slice($cols, 0, count($params))));
        $sql = "INSERT INTO fishery_profiles ($colList) VALUES (" . implode(', ', $vals) . ")";
        self::query($sql, $params);
        return (int) $data['id'];
    }

    /**
     * Update a fishery profile.
     */
    public static function update(int|string $id, array $data): bool
    {
        $updates = [];
        $params = [];

        foreach (['rsbsa_no', 'fishr_no', 'status'] as $col) {
            if (array_key_exists($col, $data)) {
                $updates[] = "`$col` = ?";
                $params[] = $data[$col];
            }
        }

        if (empty($updates)) {
            return true;
        }

        $params[] = $id;
        $sql = "UPDATE fishery_profiles SET " . implode(', ', $updates) . " WHERE id = ?";
        return self::query($sql, $params)->rowCount() >= 0;
    }

    /**
     * Create or update a fishery profile for an existing profile.
     */
    public static function upsert(int $profileId, array $data): int
    {
        $existing = self::rawOne(
            'SELECT id FROM fishery_profiles WHERE id = ?',
            [$profileId]
        );

        if ($existing) {
            self::update($profileId, $data);
            return $profileId;
        } else {
            return self::insert(['id' => $profileId] + $data);
        }
    }
}
