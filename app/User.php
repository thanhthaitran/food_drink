<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserInfo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use App\SearchTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, SearchTrait, Sortable, HasApiTokens;

     /**
     * User role
     *
     * @type int
     */
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;
    
    protected $search = [
        'name',
        'email'
    ];
    
    public $sortable = [
        'id',
        'name',
        'email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'prodvider',
        'deleted_at',
        'provider_user_id',
    ];

    /**
     * User Belong To UserInfo
     *
     * @return mixed
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class)->withTrashed();
    }

    /**
     * User Has Many Posts
     *
     * @return mixed
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * User Has Many Orders
     *
     * @return mixed
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * User Has Many Notes
     *
     * @return mixed
     */
    public function notes()
    {
        return $this->hasMany('App\Note');
    }


    /**
     * User Has Many ShippingAddresses
     *
     * @return mixed
     */
    public function shippingAddresses()
    {
        return $this->hasMany('App\ShippingAddress');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
