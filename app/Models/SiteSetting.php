<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /** @return array<string, string|null> */
    public static function values(): array
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return [];
            }

            return static::query()->pluck('value', 'key')->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /** @param array<string, string|null> $values */
    public static function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
