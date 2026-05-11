<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\DefaultTeamResolver;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFilamentAvatarUrl(): string
    {
        if ($this->avatar_url) {
            return asset('storage/'.$this->avatar_url);
        }
        $hash = md5(mb_strtolower(mb_trim($this->email)));

        return 'https://www.gravatar.com/avatar/'.$hash.'?d=mp&r=g&s=250';

    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasRole(['super_admin']),
            'control' => $this->hasAnyRole(['teknisi', 'supervisor']),
            default => false,
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // relasi ke table Plant lewat pivot table UserPlant
    public function plants(){
        return $this->belongsToMany(Plant::class, 'user_plants');
    }

    // relasi ke table PlcNotification
    public function plc_notifications(){
        return $this->hasMany(PlcNotification::class, 'updated_by');
    }

    public function spk_logs(){
        return $this->hasMany(SpkLog::class, 'updated_by');
    }
}
