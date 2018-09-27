<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'order_id',
        'content',
    ];

    /**
     * Post Belong To User
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Post Belong To Order
     *
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
