<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        // DD to debug the forwarded_to filter
        // if (filled($filters['forwarded_to'] ?? null)) {
        //     dd([
        //         'Buscado' => $filters['forwarded_to'],
        //         'Ejemplo_En_BD' => \App\Models\Email::whereNotNull('forwarded_to')->first()?->forwarded_to,
        //         'SQL' => $query->where('forwarded_to', 'like', "%{$filters['forwarded_to']}%")->toRawSql(),
        //         'Resultado_Query' => $query->where('forwarded_to', 'like', "%{$filters['forwarded_to']}%")->get(),
        //     ]);
        // }

        return $query
            ->when(filled($filters['sender'] ?? null), function ($q) use ($filters){
                $q->where('sender', 'like', trim("%{$filters['sender']}%"));
            })
            ->when(filled($filters['subject'] ?? null), function ($q) use ($filters) {
                $q->where('subject', 'like', trim("%{$filters['subject']}%"));
            })
            ->when(filled($filters['forwarded_to'] ?? null), function ($q) use ($filters) {
                $q->where('forwarded_to', 'like', trim("%{$filters['forwarded_to']}%"));
            })
            ->when(filled($filters['status'] ?? null), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(filled($filters['from_date'] ?? null), function ($q) use ($filters) {
                $q->whereDate('created_at', '>=', $filters['from_date']);
            })
            ->when(filled($filters['to_date'] ?? null), function ($q) use ($filters) {
                $q->whereDate('created_at', '<=', $filters['to_date']);
            });
    }
}

