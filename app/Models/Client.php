<?php

namespace App\Models;

use App\Aggregates\Clients\ClientAccountAggregate;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Client extends Model
{
    use CrudTrait, HasFactory, RevisionableTrait, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['name', 'active', 'icon', 'logo', 'account_owner'];

    protected $revisionCreationsEnabled = true;

    public function identifiableName()
    {
        return $this->name;
    }

    public function account_owner_user()
    {
        return $this->belongsTo('App\Models\User', 'account_owner', 'id');
    }

    public static function boot()
    {
        parent::boot();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($client) {
            ClientAccountAggregate::retrieve($client->id)
                ->createClient($client->toArray())
                ->setDefaultCreditGateway($client->id)
                ->setNewClientApiToken($client->id)
                ->enableDefaultSMSSettings($client->id)
                ->createNewMenuOptions($client->id, $client->icon)
                ->persist();
        });

        static::updated(function ($client) {
            ClientAccountAggregate::retrieve($client->id)
                ->updateClient($client->toArray())
                ->setAccountOwner($client->account_owner)
                ->persist();
        });
    }

    public function assigned_icon()
    {
        return $this->hasOne('App\Models\Utility\IconsSet', 'id', 'icon');
    }

    public function iconsset()
    {
        return $this->assigned_icon();
    }

    public function company_name()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('company_name');
    }

    public function address1()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('address1');
    }

    public function address2()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('address2');
    }

    public function city()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('city');
    }

    public function state()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('state');
    }

    public function zip()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('zip');
    }

    public function phone()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('phone');
    }

    public function website()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('website');
    }

    public function email()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id')
            ->whereName('email');
    }

    public function details()
    {
        return $this->hasMany('App\Models\ClientDetails', 'client_id', 'id');
    }

    public function merchants()
    {
        return $this->hasMany('App\Models\Merchant', 'client_id', 'id');
    }

    public function enabled_gateways()
    {
        return $this->hasMany('App\Models\PaymentGateways\ClientEnabledPaymentProviders', 'client_id', 'id');
    }

    public function enabled_sms()
    {
        return $this->hasMany('App\Models\SMS\ClientEnabledSmsProviders', 'client_id', 'id');
    }

    public function shops()
    {
        return $this->hasMany('App\Models\Shops\Shop', 'client_id', 'id');
    }

    public function getSystemUserId()
    {
        return is_null(backpack_user()) ? 'System' : backpack_user()->id;
    }
}
