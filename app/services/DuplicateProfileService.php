<?php
/**
 * Duplicate profile check for new schema (profiles + livelihood tables).
 *
 * check($data, $excludeId) returns:
 *   ['level' => 'clear' | 'warning' | 'blocked', 'matches' => [ match, ... ]]
 *
 * match = id, label, meta, reason, strength ('exact_id' | 'strong' | 'possible'), url
 *
 *   blocked  an RSBSA / FishR number is already registered (cannot be overridden)
 *   warning  same name + birthdate (strong) or same / near-identical name in the same barangay (possible)
 *   clear    nothing similar found
 */
class DuplicateProfileService
{
    private const MAX_MATCHES = 5;
    private const NEAR_DISTANCE = 2;

    public static function check(array $data, ?int $excludeId = null): array
    {
        $matches = [];
        $seen    = [];

        // 1. Exact registry numbers -> blocked
        foreach (['rsbsa_no' => 'RSBSA number', 'fishr_no' => 'FishR number'] as $col => $label) {
            $val = trim((string) ($data[$col] ?? ''));
            if ($val === '') {
                continue;
            }

            // For RSBSA, check both farming_profiles and fishery_profiles
            if ($col === 'rsbsa_no') {
                foreach (['farming_profiles', 'fishery_profiles'] as $table) {
                    $sql    = "SELECT p.id, p.last_name, p.first_name, p.middle_name, p.suffix, p.birthdate, p.deleted_at,
                               b.name AS barangay_name, $table.rsbsa_no
                               FROM $table
                               JOIN profiles p ON p.id = $table.id
                               LEFT JOIN barangays b ON b.id = p.barangay_id
                               WHERE $table.rsbsa_no = ? AND $table.deleted_at IS NULL";
                    $params = [$val];
                    if ($excludeId) {
                        $sql .= ' AND p.id <> ?';
                        $params[] = $excludeId;
                    }
                    foreach (Model::raw($sql, $params) as $row) {
                        if (isset($matches[$row['id']])) {
                            continue;
                        }
                        $reason = "$label already registered";
                        $matches[$row['id']] = self::makeMatch($row, $reason, 'exact_id');
                    }
                }
            } elseif ($col === 'fishr_no') {
                $sql    = "SELECT p.id, p.last_name, p.first_name, p.middle_name, p.suffix, p.birthdate, p.deleted_at,
                           b.name AS barangay_name, fishery_profiles.fishr_no
                           FROM fishery_profiles
                           JOIN profiles p ON p.id = fishery_profiles.id
                           LEFT JOIN barangays b ON b.id = p.barangay_id
                           WHERE fishery_profiles.fishr_no = ? AND fishery_profiles.deleted_at IS NULL";
                $params = [$val];
                if ($excludeId) {
                    $sql .= ' AND p.id <> ?';
                    $params[] = $excludeId;
                }
                foreach (Model::raw($sql, $params) as $row) {
                    if (isset($matches[$row['id']])) {
                        continue;
                    }
                    $reason = "$label already registered";
                    $matches[$row['id']] = self::makeMatch($row, $reason, 'exact_id');
                }
            }
        }

        // 2. Same / similar name
        $first = trim((string) ($data['first_name'] ?? ''));
        $last  = trim((string) ($data['last_name'] ?? ''));
        if ($first !== '' && $last !== '') {
            $key       = Profile::makeNameKey($last, $first);
            $birthdate = !empty($data['birthdate']) ? $data['birthdate'] : null;
            $barangay  = !empty($data['barangay_id']) ? (int) $data['barangay_id'] : null;

            $sql    = 'SELECT p.id, p.last_name, p.first_name, p.middle_name, p.suffix, p.birthdate, p.deleted_at, p.name_key, p.barangay_id,
                       b.name AS barangay_name
                       FROM profiles p
                       LEFT JOIN barangays b ON b.id = p.barangay_id
                       WHERE p.deleted_at IS NULL AND (p.name_key = ?';
            $params = [$key];
            if ($barangay) {
                $sql .= ' OR p.barangay_id = ?';
                $params[] = $barangay;
            }
            if ($birthdate) {
                $sql .= ' OR p.birthdate = ?';
                $params[] = $birthdate;
            }
            $sql .= ')';
            if ($excludeId) {
                $sql .= ' AND p.id <> ?';
                $params[] = $excludeId;
            }

            foreach (Model::raw($sql, $params) as $row) {
                if (isset($matches[$row['id']])) {
                    continue;
                }
                $sameKey   = $row['name_key'] === $key;
                $distance  = $sameKey ? 0 : levenshtein($key, (string) $row['name_key']);
                $nearName  = $distance <= self::NEAR_DISTANCE;
                $sameBirth = $birthdate && $row['birthdate'] === $birthdate;
                $sameBrgy  = $barangay && (int) $row['barangay_id'] === $barangay;

                if ($nearName && $sameBirth) {
                    $reason = $sameKey ? 'Same name and birthdate' : 'Similar name and same birthdate';
                    $matches[$row['id']] = self::makeMatch($row, $reason, 'strong');
                } elseif ($nearName && $sameBrgy) {
                    $reason = $sameKey ? 'Same name in the same barangay' : 'Similar name in the same barangay';
                    $matches[$row['id']] = self::makeMatch($row, $reason, 'possible');
                } elseif ($sameKey) {
                    $matches[$row['id']] = self::makeMatch($row, 'Same name', 'possible');
                }
            }
        }

        // order: blocking first, then strong, then possible
        $rank = ['exact_id' => 0, 'strong' => 1, 'possible' => 2];
        usort($matches, fn ($a, $b) => $rank[$a['strength']] <=> $rank[$b['strength']]);
        $matches = array_slice($matches, 0, self::MAX_MATCHES);

        $level = 'clear';
        if ($matches) {
            $level = in_array('exact_id', array_column($matches, 'strength'), true) ? 'blocked' : 'warning';
        }
        return ['level' => $level, 'matches' => $matches];
    }

    /** For edits: only check when something that identifies the person actually changed. */
    public static function identityChanged(array $stored, array $data): bool
    {
        foreach (['last_name', 'first_name', 'birthdate', 'barangay_id', 'rsbsa_no', 'fishr_no'] as $col) {
            if ((string) ($stored[$col] ?? '') !== (string) ($data[$col] ?? '')) {
                return true;
            }
        }
        return false;
    }

    /** JSON the warning modal understands. */
    public static function toPayload(array $result): array
    {
        $blocked = $result['level'] === 'blocked';
        return [
            'level'          => $result['level'],
            'title'          => $blocked ? 'Already Registered' : 'Possible Duplicate Found',
            'message'        => $blocked
                ? 'This registry number already belongs to an existing profile. Use the existing profile instead of creating a new one.'
                : 'We found profiles that look like this person. Please check them before registering a new one.',
            'matches'        => $result['matches'],
            'requires_reason' => !$blocked,
            'confirm_label'  => 'Register anyway',
            'reason_label'   => 'Reason for registering anyway',
            'reason_field'   => 'duplicate_override_reason',
            'reason_min'     => 5,
            'match_action'   => 'Use this profile',
        ];
    }

    private static function makeMatch(array $row, string $reason, string $strength): array
    {
        $bits = [];
        if (!empty($row['barangay_name'])) {
            $bits[] = $row['barangay_name'];
        }
        if (!empty($row['birthdate'])) {
            $bits[] = 'Born ' . format_date($row['birthdate']);
        }
        return [
            'id'       => (int) $row['id'],
            'label'    => Profile::fullName($row),
            'meta'     => implode(' · ', $bits),
            'reason'   => $reason,
            'strength' => $strength,
            'url'      => url('/profiles/' . $row['id']),
        ];
    }
}
