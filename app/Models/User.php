<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
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

    public static function boot(){
        parent::boot();
        static::created(function($model){
            $model->generateOtpCode();
        });
    }

    public function generateOtpCode()
    {
        do {
            $randomNumber = mt_rand(100000, 999999);
            $checkOtpCode = OtpCodes::where('otp', $randomNumber)->exists();
        } while ($checkOtpCode);

        $now = Carbon::now();
        $otpCode = OtpCodes::create([
            'user_id' => $this->id,
            'otp' => $randomNumber,
            'valid_until' => $now->addMinutes(5),
        ]);
    }

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

    public function deal_project_users(){
        return $this->hasMany(DealProjectUsers::class, 'user_id');
    }

    public function otpCode()
    {
        return $this->hasOne(OtpCodes::class, 'user_id');
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
