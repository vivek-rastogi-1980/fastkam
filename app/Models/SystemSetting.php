<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Retrieve a setting value cast to its type.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting || $setting->value === null) {
            return $default;
        }

        return match ($setting->type) {
            'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $setting->value,
            'float', 'decimal', 'double' => (float) $setting->value,
            'json', 'array' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Store or update a setting value.
     */
    public static function set(string $key, mixed $value, string $type = 'string', ?string $group = 'tax', ?string $description = null): static
    {
        $stringValue = is_array($value) ? json_encode($value) : (string) $value;

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'group' => $group ?? 'tax',
                'description' => $description,
            ]
        );
    }

    /**
     * Get the active GST rate percentage.
     * Returns 0.00 if GST is disabled.
     */
    public static function getGstRate(): float
    {
        $enabled = static::get('gst_enabled', true);
        if (!$enabled) {
            return 0.00;
        }

        return (float) static::get('gst_rate_percent', 18.00);
    }

    /**
     * Check if GST surcharge is active.
     */
    public static function isGstEnabled(): bool
    {
        return (bool) static::get('gst_enabled', true);
    }

    /**
     * Calculate GST breakdown for a given base amount.
     * Returns an array with base_amount, gst_rate, gst_amount, and total_amount.
     *
     * @return array{base_amount: float, gst_rate: float, gst_amount: float, total_amount: float}
     */
    public static function calculateGst(float $amount): array
    {
        $rate = static::getGstRate();
        $gstAmount = $rate > 0 ? round($amount * ($rate / 100), 2) : 0.00;
        $total = round($amount + $gstAmount, 2);

        return [
            'base_amount' => round($amount, 2),
            'gst_rate' => $rate,
            'gst_amount' => $gstAmount,
            'total_amount' => $total,
        ];
    }
}
