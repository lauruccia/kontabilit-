<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationsLog extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'channel',
        'type',
        'recipient',
        'subject',
        'status',
        'payload',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'sent_at' => 'datetime',
        ];
    }
}
