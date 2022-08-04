<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property boolean $is_admin
 * @property integer $credit
 * @property string $password
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Cart[] $carts
 * @property Comment[] $comments
 * @property Order[] $orders
 */
class User extends Model
{
    use HasFactory;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'is_admin', 'credit', 'password', 'phone', 'city', 'address', 'remember_token', 'created_at', 'updated_at'];

    protected $hidden = ['password'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
