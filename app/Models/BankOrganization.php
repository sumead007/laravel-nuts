<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankOrganization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number_account',
        'name_account',
        'name_bank',
        'admin_id',
    ];

    public function top_up()
    {
        return $this->hasMany(TopUp::class, 'id');
    }


}
