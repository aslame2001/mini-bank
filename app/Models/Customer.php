<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'balance'];
    protected $hidden = ['password'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
