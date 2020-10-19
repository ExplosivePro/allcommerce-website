<?php

namespace AllCommerce;

use AllCommerce\Clients;
use AllCommerce\Jobs\User\OnboardNewUser;
use Backpack\CRUD\CrudTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use CrudTrait, HasRolesAndAbilities, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'client_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function username()
    {
        return 'username'; //or return the field which you want to use.
    }

    public function client()
    {
        return $this->hasOne('AllCommerce\Clients', 'id', 'client_id');
    }


    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            OnboardNewUser::dispatch($user, backpack_user())->onQueue('allcommerce-'.env('APP_ENV').'-emails');
        });
    }

    public function isHostUser()
    {
        return $this->client_id == Clients::getHostClient();
    }

    public function getActiveClient()
    {
        $results = $this->client()->first()->name;

        if(session()->has('active_client'))
        {
            $client_id = session()->get('active_client');
            $results = Clients::find($client_id)->name;
        }

        return $results;
    }

    public function getActiveClientId()
    {
        $results = $this->client()->first()->id;

        if(session()->has('active_client'))
        {
            $results = session()->get('active_client');
        }

        return $results;
    }
}
