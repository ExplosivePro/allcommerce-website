<?php

namespace App\Models;

use App\Aggregates\Users\UserProfileAggregate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Venturecraft\Revisionable\RevisionableTrait;

class User extends Authenticatable
{
    use CrudTrait, HasFactory, HasRolesAndAbilities, Notifiable, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function merchants()
    {
        return $this->hasMany('App\Models\Merchant', 'client_id', 'client_id');
    }

    public function shops()
    {
        return $this->hasMany('App\Models\Shops\Shop', 'client_id', 'client_id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id');
    }

    public function first_name()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('first_name');
    }

    public function last_name()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('last_name');
    }

    public function city()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('city');
    }

    public function state()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('state');
    }

    public function zip()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('zip');

    }

    public function address1()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('address1');
    }

    public function address2()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('address2');
    }

    public function phone()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('phone');
    }

    public function profile_image()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id')
            ->whereName('profile_image');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user)
        {
            $aggy = UserProfileAggregate::retrieve($user->id)
                ->createUser($user->toArray());

            // If running in the cli, then we are creating an admin for sure.
            if (strpos(php_sapi_name(), 'cli') !== false) {
                $aggy = $aggy->assignAdmin();
            }

            $aggy->persist();
        });
    }

    public function getSystemUserId()
    {
        return is_null(backpack_user()) ? 'System' : backpack_user()->id;
    }
}
