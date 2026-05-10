<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'github_id',
        'password',
        'role_id',
        'avatar',
        'avatar_disk',
        'bio',
        'github_url',
        'linkedin_url',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Media rows live on the Supabase DB connection; uploaded_by matches this user’s id (no cross-DB FK).
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->name === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role?->name, ['admin', 'superadmin']);
    }

    public function isMember(): bool
    {
        return in_array($this->role?->name, ['member', 'admin', 'superadmin']);
    }
}
