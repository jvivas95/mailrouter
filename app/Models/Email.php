<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //
    protected $fillable = [
        'uid', 'sender', 'subject',
        'body', 'forwarded_to', 'forwarded_at',
        'attachments_count', 'status'
    ];

    // Scopes - reusable filters
    public function scopeForwarded($query) {

        return $query->where('status', 'forwarded');
    }

    public function scopePending($query) {

        return $query->where('status', 'pending');
    }
}
