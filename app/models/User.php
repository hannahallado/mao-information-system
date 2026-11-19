<?php
class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'full_name', 'username', 'password_hash', 'role', 'sector', 'is_active', 'last_login_at',
    ];
}
