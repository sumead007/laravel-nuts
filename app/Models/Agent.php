<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'telephone',
        'wallet',
        'share_percentage',
        'admin_id',
    ];

    public function bank_organization(){
        return $this->hasMany(BankOrganization::class,'id');
    }
}
