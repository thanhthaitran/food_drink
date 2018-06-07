<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SearchTrait;

class Product extends Model
{
    use SearchTrait, SoftDeletes;
    
    protected $search = [
        'name',
    ];

    public $sortable = ['name'];

    protected $fillable=[
        'name',
        'category_id',
        'description',
        'price',
        'quantity',
        'preview',
        'avg_rate',
        'sum_rate',
        'total_rate',
        'deleted_at'
    ];

    /**
     * Product Belong To Category
     *
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Image Has Many Categories
     *
     * @return mixed
     */
    public function images()
    {
        return $this->hasMany('App\Image');
    }

    /**
     * Order Has Many Order Detail
     *
     * @return mixed
     */
    public function orderDetail()
    {
        return $this->hasMany('App\OrderDetail');
    }

    /**
     * Product Has Many Category
     *
     * @return mixed
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
