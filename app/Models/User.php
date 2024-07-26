<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\IntegrationProvider;
use App\Models\Traits\HasPublicId;
use App\Models\Traits\HasUuidV7;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Domain;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuidV7, HasPublicId, HasRoles, HasPermissions, SoftDeletes, TwoFactorAuthenticatable, CausesActivity;

    protected string $publicIdPrefix = "user_";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'public_id',
        'name',
        'email',
        'password',
        "temporary_password",
        "email_verify_resend_at",
        "locale",
        "prev_passwords",
        "password_changed_at",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        "prev_passwords",
        'remember_token',
    ];

    protected $appends = [
        "role"
    ];

    public static function booted(): void {
        static::creating(function (Model $user) {

            if(is_null($user->locale)) {
                $user->locale = config("app.locale");
            }
        });
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
            'email_verify_resend_at' => 'datetime',
            "password_changed_at" => "datetime",
            'password' => 'hashed',
            "prev_passwords" => "encrypted:array",
            "temporary_password" => "boolean",
        ];
    }

    public function needsNewPassword(): bool {
        return $this->temporary_password
            || Carbon::parse($this->password_changed_at)->clone()->addMonths(6)->isBefore(now())
            || is_null($this->password_changed_at);
    }

    public function isNewPasswordPreviouslyUsed(string $password): bool {
        if(Hash::isHashed($password)) {
            return false;
        }

        if(Hash::check($password, $this->password)) {
            return true;
        }

        return collect($this->prev_passwords)
            ->contains(fn($prevPassword) => Hash::check($password, $prevPassword));
    }

    public function addPasswordToPreviouslyUsed(string $password): void {
        if(! Hash::isHashed($password)) {
            $password = Hash::make($password);
        }

        $this->prev_passwords = array_merge($this->prev_passwords ?? [], [$password]);
        $this->saveQuietly();
    }

    public function GetRoleAttribute(): string {
        return $this->roles()->first()?->name ?? "User";
    }

    public function sendEmailVerificationNotification(): void {
        $this->update(["email_verify_resend_at" => now()->addMinutes(5)]);
        $this->notify(new \App\Notifications\Auth\QueuedVerifyEmail);
    }

    public function sendPasswordResetNotification($token): void {
        $this->notify(new \App\Notifications\Auth\QueuedResetPassword($token));
    }

    public function userIntegrations(): HasMany {
        return $this->hasMany(UserIntegration::class);
    }

    public function hasIntegration(IntegrationProvider $provider): bool {
        return $this->userIntegrations()
            ->where("provider", $provider)
            ->exists();
    }

    public function hasAccessToIntegration(IntegrationProvider $provider): bool {
        return $provider->hasAccess($this);
    }

    public function customers(): HasMany {
        return $this->hasMany(Customer::class);
    }
    public function domains(): HasMany {
        return $this->hasMany(Domain::class);
    }
    public function getIntegration(IntegrationProvider $provider): ?UserIntegration {
        return $this->userIntegrations()
            ->where("provider", $provider)
            ->first();
    }
}
