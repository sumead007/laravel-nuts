<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_account',
        'name_bank',
        'number_account',
        'image',
        'money',
        'status',
        'bank_or_id',
        'user_id',
    ];

    public function bank_organization()
    {
        return $this->belongsTo(BankOrganization::class, 'bank_or_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
