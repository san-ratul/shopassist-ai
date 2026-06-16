<?php

namespace SanRatul\ShopAssist\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property bool $is_encrypted
 * @property string $value
 */
class Setting extends Model
{
    protected $table = 'shopassist_settings';

    protected $fillable = [
        'key',
        'value',
        'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];
}