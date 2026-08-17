<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileAppConfig extends Model
{
    protected $fillable = ['config_key', 'label', 'value', 'active'];

    protected $casts = ['value' => 'array', 'active' => 'boolean'];

    /**
     * Resolve a config value to a plain string, handling the various storage
     * formats (PHP-serialized, JSON-encoded, arrays, or raw scalars).
     */
    public static function configValue(string $key, string $default = ''): string
    {
        $raw = static::where('config_key', $key)->value('value');

        if (is_array($raw)) {
            return $raw['ar'] ?? $raw['en'] ?? $default;
        }

        if (! is_string($raw) || $raw === '') {
            return $default;
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded['ar'] ?? $decoded['en'] ?? $default;
        }
        if (is_string($decoded)) {
            return $decoded;
        }

        $unval = @unserialize($raw);
        if (is_array($unval)) {
            return $unval['ar'] ?? $unval['en'] ?? $default;
        }
        if (is_string($unval)) {
            return $unval;
        }

        return $raw;
    }
}
