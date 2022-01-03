<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryUser extends Model
{
    protected $table = 'secondary_users';

    protected $hidden = [
        'password',
        'remember_token',
        'ownership_id',
    ];

    public function owner(){
        return $this->belongsTo(User::class, 'ownership_id', 'id');
    }
}
