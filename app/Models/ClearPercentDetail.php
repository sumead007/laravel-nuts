<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearPercentDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clear_id',
        'bet_detail_id',
    ];

    public function clear_percents()
    {
        return $this->belongsTo(ClearPercent::class, 'id');
    }

    public function bet_details()
    {
        return $this->belongsTo(BetDetail::class, 'id');
    }
    public $timestamps = false;
}
