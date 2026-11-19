<?php
/**
 * Server-side validation.
 *
 *   $v = Validator::make($_POST, [
 *       'last_name' => 'required|max:60',
 *       'sex'       => 'required|in:Male,Female',
 *       'birthdate' => 'nullable|date',
 *       'rsbsa_no'  => 'nullable|unique:farmers,rsbsa_no',          // add ,ignoreId when editing
 *   ]);
 *   if ($v->fails()) { ... $v->errors() ... }
 *   $clean = $v->validated();   // trimmed values, '' => null
 *
 * Rules: required, nullable, max:n, min:n, numeric, integer, date, email, in:a,b,c, unique:table,column[,ignoreId]
 */
class Validator
{
    private array $errors = [];
    private array $clean = [];

    private function __construct(private array $data, private array $rules, private array $labels)
    {
        $this->run();
    }

    public static function make(array $data, array $rules, array $labels = []): self
    {
        return new self($data, $rules, $labels);
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        return $this->clean;
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $raw   = $this->data[$field] ?? null;
            $value = is_string($raw) ? trim($raw) : $raw;
            $empty = $value === null || $value === '';
            $rules = array_filter(explode('|', $ruleString));

            $this->clean[$field] = $empty ? null : $value;

            if ($empty) {
                if (in_array('required', $rules, true)) {
                    $this->fail($field, $this->label($field) . ' is required.');
                }
                continue;   // other rules apply only when a value is present
            }

            foreach ($rules as $rule) {
                [$name, $arg] = array_pad(explode(':', $rule, 2), 2, null);
                $this->check($field, (string) $value, $name, $arg);
            }
        }
    }

    private function check(string $field, string $value, string $name, ?string $arg): void
    {
        $label = $this->label($field);
        switch ($name) {
            case 'max':
                if (mb_strlen($value) > (int) $arg) {
                    $this->fail($field, "$label must not exceed $arg characters.");
                }
                break;
            case 'min':
                if (mb_strlen($value) < (int) $arg) {
                    $this->fail($field, "$label must be at least $arg characters.");
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->fail($field, "$label must be a number.");
                }
                break;
            case 'integer':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->fail($field, "$label must be a whole number.");
                }
                break;
            case 'date':
                $d = DateTime::createFromFormat('Y-m-d', $value);
                if (!$d || $d->format('Y-m-d') !== $value) {
                    $this->fail($field, "$label must be a valid date (YYYY-MM-DD).");
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->fail($field, "$label must be a valid email address.");
                }
                break;
            case 'in':
                if (!in_array($value, explode(',', (string) $arg), true)) {
                    $this->fail($field, "$label has an invalid selection.");
                }
                break;
            case 'unique':
                [$table, $column, $ignore] = array_pad(explode(',', (string) $arg), 3, null);
                if ($this->exists($table, $column, $value, $ignore)) {
                    $this->fail($field, "$label already exists.");
                }
                break;
        }
    }

    private function exists(string $table, string $column, string $value, ?string $ignoreId): bool
    {
        foreach ([$table, $column] as $name) {
            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
                throw new InvalidArgumentException("Invalid identifier in unique rule: $name");
            }
        }
        $sql    = "SELECT 1 FROM `$table` WHERE `$column` = ?";
        $params = [$value];
        if ($ignoreId !== null && $ignoreId !== '') {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = Database::connect()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }

    private function label(string $field): string
    {
        return $this->labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    private function fail(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}
