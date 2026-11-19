<?php
/**
 * Base model with PDO helpers. Child classes set $table (and optionally the rest):
 *
 *   class Farmer extends Model {
 *       protected static string $table = 'farmers';
 *       protected static array $fillable = ['last_name', 'first_name', ...];
 *       protected static bool $softDeletes = true;
 *   }
 *
 * Table/column names come from code (never from user input) and are checked
 * against a strict pattern; all VALUES are bound parameters.
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $pk = 'id';
    protected static array $fillable = [];
    protected static bool $softDeletes = false;   // uses deleted_at
    protected static bool $timestamps = true;     // created_at / updated_at
    protected static bool $trackCreator = false;  // created_by (logged-in user)

    protected static function db(): PDO
    {
        return Database::connect();
    }

    /** Run any prepared query. */
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function raw(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function rawOne(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    private static function ident(string $name): string
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            throw new InvalidArgumentException("Invalid identifier: $name");
        }
        return '`' . $name . '`';
    }

    private static function notDeleted(string $prefix = 'WHERE'): string
    {
        return static::$softDeletes ? " $prefix `deleted_at` IS NULL" : '';
    }

    public static function find(int|string $id): ?array
    {
        $sql = 'SELECT * FROM ' . self::ident(static::$table)
             . ' WHERE ' . self::ident(static::$pk) . ' = ?'
             . self::notDeletedAnd() . ' LIMIT 1';
        return self::rawOne($sql, [$id]);
    }

    private static function notDeletedAnd(): string
    {
        return static::$softDeletes ? ' AND `deleted_at` IS NULL' : '';
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $sql = 'SELECT * FROM ' . self::ident(static::$table) . self::notDeleted();
        return self::raw($sql . ' ORDER BY ' . self::safeOrder($orderBy));
    }

    /** Equality filters: Farmer::where(['barangay_id' => 3, 'status' => 'active']) */
    public static function where(array $conditions, string $orderBy = 'id DESC'): array
    {
        $parts  = [];
        $params = [];
        foreach ($conditions as $col => $val) {
            $parts[]  = self::ident($col) . ' = ?';
            $params[] = $val;
        }
        if (static::$softDeletes) {
            $parts[] = '`deleted_at` IS NULL';
        }
        $sql = 'SELECT * FROM ' . self::ident(static::$table)
             . ($parts ? ' WHERE ' . implode(' AND ', $parts) : '')
             . ' ORDER BY ' . self::safeOrder($orderBy);
        return self::raw($sql, $params);
    }

    public static function count(string $where = '', array $params = []): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . self::ident(static::$table);
        $cond = [];
        if (static::$softDeletes) {
            $cond[] = '`deleted_at` IS NULL';
        }
        if ($where !== '') {
            $cond[] = "($where)";
        }
        if ($cond) {
            $sql .= ' WHERE ' . implode(' AND ', $cond);
        }
        return (int) self::query($sql, $params)->fetchColumn();
    }

    /**
     * Paginated list. $where is a SQL fragment written in code with ? placeholders,
     * e.g. paginate('barangay_id = ? AND status = ?', [$b, $s], $page, 'last_name ASC')
     */
    public static function paginate(string $where = '', array $params = [], int $page = 1,
                                    string $orderBy = 'id DESC', ?int $perPage = null): array
    {
        $perPage = $perPage ?? (int) config('per_page', 15);
        $page    = max(1, $page);
        $total   = static::count($where, $params);
        $pages   = max(1, (int) ceil($total / $perPage));
        $page    = min($page, $pages);
        $offset  = ($page - 1) * $perPage;

        $cond = [];
        if (static::$softDeletes) {
            $cond[] = '`deleted_at` IS NULL';
        }
        if ($where !== '') {
            $cond[] = "($where)";
        }
        $sql = 'SELECT * FROM ' . self::ident(static::$table)
             . ($cond ? ' WHERE ' . implode(' AND ', $cond) : '')
             . ' ORDER BY ' . self::safeOrder($orderBy)
             . ' LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;

        return [
            'data'     => self::raw($sql, $params),
            'total'    => $total,
            'page'     => $page,
            'pages'    => $pages,
            'per_page' => $perPage,
        ];
    }

    /** Insert only fillable columns; returns the new id. */
    public static function insert(array $data): int
    {
        $data = array_intersect_key($data, array_flip(static::$fillable));
        if (static::$timestamps) {
            $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        }
        if (static::$trackCreator && Auth::check()) {
            $data['created_by'] = Auth::id();
        }
        $cols = array_map(fn ($c) => self::ident($c), array_keys($data));
        $sql  = 'INSERT INTO ' . self::ident(static::$table)
              . ' (' . implode(', ', $cols) . ') VALUES (' . implode(', ', array_fill(0, count($data), '?')) . ')';
        self::query($sql, array_values($data));
        return (int) self::db()->lastInsertId();
    }

    public static function update(int|string $id, array $data): bool
    {
        $data = array_intersect_key($data, array_flip(static::$fillable));
        if (static::$timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        $sets = implode(', ', array_map(fn ($c) => self::ident($c) . ' = ?', array_keys($data)));
        $sql  = 'UPDATE ' . self::ident(static::$table) . " SET $sets WHERE " . self::ident(static::$pk) . ' = ?';
        return self::query($sql, [...array_values($data), $id])->rowCount() >= 0;
    }

    /** Soft delete. The plan says records are never hard-deleted. */
    public static function archive(int|string $id): bool
    {
        if (!static::$softDeletes) {
            throw new LogicException(static::class . ' does not support soft deletes.');
        }
        $sql = 'UPDATE ' . self::ident(static::$table) . ' SET `deleted_at` = ? WHERE ' . self::ident(static::$pk) . ' = ?';
        return self::query($sql, [date('Y-m-d H:i:s'), $id])->rowCount() > 0;
    }

    public static function restore(int|string $id): bool
    {
        $sql = 'UPDATE ' . self::ident(static::$table) . ' SET `deleted_at` = NULL WHERE ' . self::ident(static::$pk) . ' = ?';
        return self::query($sql, [$id])->rowCount() > 0;
    }

    /** Allow only "column [ASC|DESC]" so ORDER BY can't be abused. */
    private static function safeOrder(string $orderBy): string
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_.]*(\s+(ASC|DESC))?(\s*,\s*[A-Za-z_][A-Za-z0-9_.]*(\s+(ASC|DESC))?)*$/i', $orderBy)) {
            throw new InvalidArgumentException("Invalid ORDER BY: $orderBy");
        }
        return $orderBy;
    }
}
