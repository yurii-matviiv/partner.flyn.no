<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PartnerUser extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $table = 'partner_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'partner-panel' && $this->is_active;
    }

    public function getFilamentName(): string
    {
        return filled($this->name) ? $this->name : $this->email;
    }
}
