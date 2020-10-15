<?php

namespace AnchorCMS\Models\PaymentGateways;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProviderTypes extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $primaryKey  = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
    ];

    public function payment_gateways()
    {
        return $this->hasMany('AnchorCMS\Models\PaymentGateways\PaymentProviders', 'provider_type', 'id');
    }

    public function getAllCreditGateways()
    {
        $results = [];

        $type = $this->whereSlug('credit')->with('payment_gateways')->first();

        if((!is_null($type)) && (count($type->payment_gateways) > 0))
        {
            $results = $type->payment_gateways->toArray();
        }

        return $results;
    }

    public function getAllExpressGateways()
    {
        $results = [];

        $type = $this->whereSlug('express')->with('payment_gateways')->first();

        if((!is_null($type)) && (count($type->payment_gateways) > 0))
        {
            $results = $type->payment_gateways->toArray();
        }

        return $results;
    }

    public function getAllInstallmentGateways()
    {
        $results = [];

        $type = $this->whereSlug('install')->with('payment_gateways')->first();

        if((!is_null($type)) && (count($type->payment_gateways) > 0))
        {
            $results = $type->payment_gateways->toArray();
        }

        return $results;
    }
}
