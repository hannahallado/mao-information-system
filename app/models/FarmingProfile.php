<?php
/** Farming livelihood profile. */
class FarmingProfile extends Model
{
    protected static string $table = 'farming_profiles';
    protected static bool $softDeletes = true;
    protected static bool $trackCreator = true;
    protected static array $fillable = [
        'id', 'rsbsa_no', 'status', 'created_by', 'created_at', 'updated_at', 'deleted_at'
    ];

    public const STATUSES = ['active' => 'Active', 'inactive' => 'Inactive', 'transferred' => 'Transferred', 'deceased' => 'Deceased'];

    /**
     * Get the associated profile.
     */
    public static function withProfile(int $id): ?array
    {
        return self::rawOne(
            'SELECT fp.*, p.last_name, p.first_name, p.middle_name, p.suffix, p.sex, p.birthdate,
                    p.contact_no, p.barangay_id, p.address_detail, p.status AS profile_status,
                    b.name AS barangay_name
             FROM farming_profiles fp
             JOIN profiles p ON p.id = fp.id
             LEFT JOIN barangays b ON b.id = p.barangay_id
             WHERE fp.id = ? AND fp.deleted_at IS NULL LIMIT 1',
            [$id]
        );
    }

    /**
     * Create a farming profile for an existing profile (custom insert to handle id as FK).
     */
    public static function insert(array $data): int
    {
        $cols = ['id', 'rsbsa_no', 'status'];
        $vals = [];
        $params = [];

        foreach ($cols as $col) {
            if (isset($data[$col])) {
                $vals[] = '?';
                $params[] = $data[$col];
            }
        }

        if (empty($params)) {
            throw new Exception('No data provided for farming profile');
        }

        $colList = implode(', ', array_map(fn ($c) => "`$c`", array_slice($cols, 0, count($params))));
        $sql = "INSERT INTO farming_profiles ($colList) VALUES (" . implode(', ', $vals) . ")";
        self::query($sql, $params);
        return (int) $data['id'];
    }

    /**
     * Update a farming profile.
     */
    public static function update(int|string $id, array $data): bool
    {
        $updates = [];
        $params = [];

        foreach (['rsbsa_no', 'status'] as $col) {
            if (array_key_exists($col, $data)) {
                $updates[] = "`$col` = ?";
                $params[] = $data[$col];
            }
        }

        if (empty($updates)) {
            return true;
        }

        $params[] = $id;
        $sql = "UPDATE farming_profiles SET " . implode(', ', $updates) . " WHERE id = ?";
        return self::query($sql, $params)->rowCount() >= 0;
    }

    /**
     * Create or update a farming profile for an existing profile.
     */
    public static function upsert(int $profileId, array $data): int
    {
        $existing = self::rawOne(
            'SELECT id FROM farming_profiles WHERE id = ?',
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
