<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackingOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'old_status',
        'new_status',
        'date_changed'
    ];

    /**
     * Shipping Belong To Order
     *
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
