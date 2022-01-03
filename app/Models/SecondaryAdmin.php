<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SecondaryAdmin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'secondary_admin';

    protected $table = 'secondary_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'user_type',
        'user_group'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'ownership_id',
    ];

    public function owner(){
        return $this->belongsTo(Admin::class, 'ownership_id', 'id');
    }
}
