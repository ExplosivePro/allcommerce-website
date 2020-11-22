<?php

namespace App\Models\PaymentGateways;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopAssignedPaymentProviders extends Model
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

    protected $fillable = ['shop_uuid', 'client_enabled_uuid', 'provider_uuid', 'merchant_uuid', 'client_uuid', 'active'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    protected $casts = [

    ];

    public function payment_provider()
    {
        return $this->belongsTo('App\Models\PaymentGateways\PaymentProviders', 'provider_uuid', 'id')
            ->with('payment_type');
    }
}
