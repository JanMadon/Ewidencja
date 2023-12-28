<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\LaravelIgnition\FlareMiddleware\AddLogs;

class Employee extends Authenticatable
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    public function addedLogs(): HasMany{
        return $this->hasMany(AddLogs::class,'employee_id', 'id');
    }

    public function xxx(): HasMany{
        return $this->hasMany(RawLogs::class,'employee_id', 'id');
    }
}
