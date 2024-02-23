<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'recipient_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function EmailsMessages() {
        return $this->hasMany(EmailMessage::class);
    } 

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::ulid();
            $model->recipient_name = $model->name;
            $model->email_pass = Str::random(8);
        });
    }
}
