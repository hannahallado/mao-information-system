<?php
/**
 * Shared human-readable IDs, one sequence per prefix and year:
 *   Farming RSBSA ID      FRM-2026-00001
 *   Fishery RSBSA ID      FSH-2026-00001
 *   Fishery FishR ID      FIR-2026-00001
 *   Program ID            PRG-2026-001
 *   Assistance ID         AST-2026-00001
 *
 * Uses the id_sequences table with MySQL's LAST_INSERT_ID(expr) idiom, so two
 * users saving at the same moment can never receive the same number.
 * Store the result in a UNIQUE column next to the numeric primary key.
 */
class IdGenerator
{
    public static function farmingRsbsaId(): string
    {
        return self::next('FRM', 5);
    }

    public static function fisheryRsbsaId(): string
    {
        return self::next('FSH', 5);
    }

    public static function fisheryFishrId(): string
    {
        return self::next('FIR', 5);
    }

    public static function programId(): string
    {
        return self::next('PRG', 3);
    }

    public static function assistanceId(): string
    {
        return self::next('AST', 5);
    }

    public static function next(string $prefix, int $pad): string
    {
        $year = (int) date('Y');
        $pdo  = Database::connect();

        $pdo->prepare(
            'INSERT INTO id_sequences (prefix, year, last_number) VALUES (?, ?, LAST_INSERT_ID(1))
             ON DUPLICATE KEY UPDATE last_number = LAST_INSERT_ID(last_number + 1)'
        )->execute([$prefix, $year]);

        $number = (int) $pdo->query('SELECT LAST_INSERT_ID()')->fetchColumn();
        return sprintf('%s-%d-%0' . $pad . 'd', $prefix, $year, $number);
    }
}

