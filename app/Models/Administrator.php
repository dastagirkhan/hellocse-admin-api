<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['email', 'password'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed', // Laravel will automatically hash passwords
    ];
}
