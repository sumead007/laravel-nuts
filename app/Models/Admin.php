<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; //ใส่ตามนี้
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'telephone',
        'username',
        'password',
        'credit',
        'share_percentage',
        'position',
        'admin_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function bank_organization(){
        return $this->hasMany(BankOrganization::class,'id');
    }

    public function user(){
        return $this->hasMany(User::class,'id');
    }
}
