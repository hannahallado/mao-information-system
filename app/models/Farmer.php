<?php
/** Example of the model pattern; extend with duplicate-check queries (name_key, etc.). */
class Farmer extends Model
{
    protected static string $table = 'farmers';
    protected static bool $softDeletes = true;
    protected static bool $trackCreator = true;
    protected static array $fillable = [
        'farmer_no', 'rsbsa_no', 'fishr_no', 'last_name', 'first_name', 'middle_name', 'suffix',
        'sex', 'birthdate', 'contact_no', 'barangay_id', 'address_detail',
        'livelihood_type', 'status', 'name_key',
    ];

    /** Normalized last+first name used for duplicate matching (plan section 7.1). */
    public static function makeNameKey(string $last, string $first): string
    {
        $key = mb_strtolower($last . $first);
        return preg_replace('/[^a-z0-9]/u', '', $key) ?? '';
    }
}
