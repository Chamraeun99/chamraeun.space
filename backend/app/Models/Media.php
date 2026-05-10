<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * Use Supabase Postgres when DB_SUPABASE_* is set; otherwise the default connection (e.g. Neon)
     * so admin media does not 500 with “connection refused” and break CORS on uncaught errors.
     */
    public function getConnectionName(): ?string
    {
        return self::resolveMediaConnection();
    }

    public static function resolveMediaConnection(): ?string
    {
        $c = config('database.connections.supabase');

        if (! empty($c['url']) || ! empty($c['host'])) {
            return 'supabase';
        }

        return config('database.default');
    }

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
