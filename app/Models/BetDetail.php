<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'user_id',
        'number',
        'money',
        'bet_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function clear_percent_details(){
        return $this->hasOne(ClearPercentDetail::class, 'bet_detail_id');
    }
}
