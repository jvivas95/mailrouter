<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    //
    protected $table = 'app_config';
    protected $fillable = ['data'];

    protected $casts = ['data' => 'array'];

    public static function get(): array {

        $config = static::first();
        return $config ? $config->data : [];
    }

    public static function set(array $data): void {

        static::updateOrCreate(['id' => 1], ['data' => $data]);
    }
}
