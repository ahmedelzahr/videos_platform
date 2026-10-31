<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Enums\UserType;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Override;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
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

    public function isAdmin(): bool{
        return $this->user_type==UserType::ADMIN;
    }

     public function isSuperAdmin(): bool{
        return $this->user_type==UserType::SUPER_ADMIN;
    }

    public function isActive(): bool{
         return $this->user_type==UserStatus::ACTIVE;
    }
     public function isBloked(): bool{
         return $this->user_type==UserStatus::BLOCKED;
    }

    public function videos(){
        return $this->hasMany(Video::class);
    }

     public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function views(){
        return $this->hasMany(View::class);
    }

    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function alert(){
        return $this->hasOne(Alert::class);
    }

    public function videosInHistory(){
        return $this->belongsToMany(Video::class,'video_user','user_id','video_id')->withTimestamps()->withPivot('id');
    }
}
