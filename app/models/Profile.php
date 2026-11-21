<?php
/** Base profile for farmers and fisherfolk. */
class Profile extends Model
{
    protected static string $table = 'profiles';
    protected static bool $softDeletes = true;
    protected static bool $trackCreator = true;
    protected static array $fillable = [
        'last_name', 'first_name', 'middle_name', 'suffix',
        'sex', 'birthdate', 'contact_no', 'barangay_id', 'address_detail',
        'status', 'name_key',
    ];

    public const STATUSES = ['active' => 'Active', 'inactive' => 'Inactive', 'transferred' => 'Transferred', 'deceased' => 'Deceased'];
    public const SEXES    = ['Male' => 'Male', 'Female' => 'Female'];

    /**
     * Normalized last+first name used for duplicate matching:
     * lowercase, accents removed, suffixes (Jr/Sr/II/III/IV) and non-letters dropped.
     */
    public static function makeNameKey(string $last, string $first): string
    {
        $map   = ['ñ' => 'n', 'á' => 'a', 'à' => 'a', 'é' => 'e', 'è' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u'];
        $first = preg_replace('/\b(jr|sr|ii|iii|iv)\.?\s*$/i', '', trim($first)) ?? $first;
        $key   = strtr(mb_strtolower($last . $first), $map);
        return preg_replace('/[^a-z0-9]/', '', $key) ?? '';
    }

    /** "Dela Cruz, Juan P. Jr." */
    public static function fullName(array $p): string
    {
        $mi   = !empty($p['middle_name']) ? ' ' . mb_substr($p['middle_name'], 0, 1) . '.' : '';
        $suf  = !empty($p['suffix']) ? ' ' . $p['suffix'] : '';
        return $p['last_name'] . ', ' . $p['first_name'] . $mi . $suf;
    }

    public static function withBarangay(int|string $id): ?array
    {
        return self::rawOne(
            'SELECT p.*, b.name AS barangay_name FROM profiles p
             LEFT JOIN barangays b ON b.id = p.barangay_id
             WHERE p.id = ? AND p.deleted_at IS NULL LIMIT 1',
            [$id]
        );
    }

    /**
     * Get livelihoods for a profile. Returns array with 'farming' and 'fishery' keys.
     */
    public static function getLivelihoods(int $profileId): array
    {
        $farming = self::rawOne(
            'SELECT * FROM farming_profiles WHERE id = ? AND deleted_at IS NULL LIMIT 1',
            [$profileId]
        );
        $fishery = self::rawOne(
            'SELECT * FROM fishery_profiles WHERE id = ? AND deleted_at IS NULL LIMIT 1',
            [$profileId]
        );
        return ['farming' => $farming, 'fishery' => $fishery];
    }

    /**
     * Searchable, filterable, paginated list.
     * $filters: q, barangay_id, livelihood_type (farming|fishery|both), status
     * Returns ['data','total','page','pages','per_page']
     */
    public static function search(array $filters, int $page = 1, ?int $perPage = null): array
    {
        $perPage = $perPage ?? (int) config('per_page', 15);
        $where   = ['p.deleted_at IS NULL'];
        $params  = [];

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $where[] = '(p.last_name LIKE ? OR p.first_name LIKE ? OR CONCAT(p.first_name, " ", p.last_name) LIKE ?
                         OR CONCAT(p.last_name, ", ", p.first_name) LIKE ?
                         OR fp.rsbsa_no LIKE ? OR fsp.rsbsa_no LIKE ? OR fsp.fishr_no LIKE ? OR p.contact_no LIKE ?)';
            array_push($params, $like, $like, $like, $like, $like, $like, $like, $like);
        }

        if (($filters['barangay_id'] ?? '') !== '') {
            $where[]  = 'p.barangay_id = ?';
            $params[] = $filters['barangay_id'];
        }

        if (($filters['status'] ?? '') !== '') {
            $where[]  = 'p.status = ?';
            $params[] = $filters['status'];
        }

        // Filter by livelihood type
        $livelyhoodType = trim((string) ($filters['livelihood_type'] ?? ''));
        if ($livelyhoodType !== '') {
            if ($livelyhoodType === 'farming') {
                $where[] = 'fp.id IS NOT NULL';
            } elseif ($livelyhoodType === 'fishery') {
                $where[] = 'fsp.id IS NOT NULL';
            } elseif ($livelyhoodType === 'both') {
                $where[] = 'fp.id IS NOT NULL AND fsp.id IS NOT NULL';
            }
        }

        $whereSql = implode(' AND ', $where);

        $total = (int) self::query(
            "SELECT COUNT(*) FROM profiles p
             LEFT JOIN farming_profiles fp ON p.id = fp.id AND fp.deleted_at IS NULL
             LEFT JOIN fishery_profiles fsp ON p.id = fsp.id AND fsp.deleted_at IS NULL
             WHERE $whereSql",
            $params
        )->fetchColumn();

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;

        $rows = self::raw(
            "SELECT p.*, b.name AS barangay_name,
                    fp.id IS NOT NULL AS has_farming, fp.rsbsa_no AS farming_rsbsa,
                    fsp.id IS NOT NULL AS has_fishery, fsp.rsbsa_no AS fishery_rsbsa, fsp.fishr_no,
                    CASE 
                        WHEN fp.id IS NOT NULL AND fsp.id IS NOT NULL THEN 'both'
                        WHEN fsp.id IS NOT NULL THEN 'fisherfolk'
                        ELSE 'farmer'
                    END AS livelihood_type
             FROM profiles p
             LEFT JOIN barangays b ON b.id = p.barangay_id
             LEFT JOIN farming_profiles fp ON p.id = fp.id AND fp.deleted_at IS NULL
             LEFT JOIN fishery_profiles fsp ON p.id = fsp.id AND fsp.deleted_at IS NULL
             WHERE $whereSql
             ORDER BY p.last_name, p.first_name, p.id
             LIMIT " . (int) $perPage . ' OFFSET ' . (int) $offset,
            $params
        );

        return ['data' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages, 'per_page' => $perPage];
    }
}
