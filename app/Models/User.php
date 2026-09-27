<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Avatar bawaan aplikasi untuk user yang belum mengunggah foto.
     */
    public const DEFAULT_AVATAR = 'admin-assets/images/avatar/avatar-placeholder.jpg';

    /**
     * Folder penyimpanan foto profil pada disk "public".
     */
    public const AVATAR_DIRECTORY = 'avatars';

    protected $fillable = [
        'name',
        'email',
        'telegram_chat_id',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function telegramUser(): HasOne
    {
        return $this->hasOne(TelegramUser::class, 'telegram_chat_id', 'telegram_chat_id');
    }

    /**
     * URL foto profil. Selalu mengembalikan URL yang bisa langsung dipakai
     * di <img src>, memakai avatar bawaan bila user belum punya foto.
     */
    public function getAvatarUrlAttribute(): string
    {
        $path = trim((string) $this->avatar);

        if ($path === '' || ! Storage::disk('public')->exists($path)) {
            return asset(self::DEFAULT_AVATAR);
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Nama role yang enak dibaca (Super Admin / Staff).
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role === 'super_admin' ? 'Super Admin' : 'Staff';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
