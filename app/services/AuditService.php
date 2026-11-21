<?php
/** Writes audit_logs rows (create / update / archive / override). Never throws into the request. */
class AuditService
{
    public static function log(string $action, string $table, int $recordId, ?array $old = null, ?array $new = null, ?string $note = null): void
    {
        try {
            Model::query(
                'INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, note, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    Auth::id(), $action, $table, $recordId,
                    $old ? json_encode($old, JSON_UNESCAPED_UNICODE) : null,
                    $new ? json_encode($new, JSON_UNESCAPED_UNICODE) : null,
                    $note, date('Y-m-d H:i:s'),
                ]
            );
        } catch (Throwable $e) {
            error_log('[audit] ' . $e->getMessage() . PHP_EOL, 3, BASE_PATH . '/storage/logs/app.log');
        }
    }

    /** Only the fields that changed: ['field' => value] for old and new. */
    public static function diff(array $old, array $new, array $fields): array
    {
        $o = $n = [];
        foreach ($fields as $f) {
            if ((string) ($old[$f] ?? '') !== (string) ($new[$f] ?? '')) {
                $o[$f] = $old[$f] ?? null;
                $n[$f] = $new[$f] ?? null;
            }
        }
        return [$o, $n];
    }
}
