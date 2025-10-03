<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail; // <-- Enable email verification
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;




class User extends Authenticatable implements FilamentUser  // I removed "   " to disable email verification use it later
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

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

    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow admins into the Filament panel
        return $this->role === 'admin';
    }

    public function initials(): string
{
    // Prefer the user's name
    $name = trim((string) $this->name);

    if ($name !== '') {
        // Take the first letter of up to the first two words
        $parts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $first = isset($parts[0]) ? mb_substr($parts[0], 0, 1) : '';
        $second = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '';
        return mb_strtoupper($first . $second);
    }

    // Fallback to email
    $email = (string) $this->email;
    return mb_strtoupper(mb_substr($email, 0, 2));
}

}
