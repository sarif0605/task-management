<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'status_account'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(){
        return $this->hasOne(Profiles::class, 'user_id');
    }

    public function hasPosition($positionName)
    {
        return $this->position()->where('name', $positionName)->exists();
    }

    public function deal_project_users(){
        return $this->hasMany(DealProjectUsers::class, 'user_id');
    }

    public function divisionsPositions()
    {
        return $this->hasMany(UserDivisiPosition::class);
    }

    public function position()
    {
        return $this->belongsToMany(Position::class, 'user_divisi_positions')->withPivot('position_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
