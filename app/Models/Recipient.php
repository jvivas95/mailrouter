<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    //
    protected $fillable = [
        'name', 'email', 'active', 'order_index'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    // Scopes - reusable filters
    public function scopeActive($query) {

        return $query->where('active', true)
            ->orderBy('order_index', 'asc');
    }

}
