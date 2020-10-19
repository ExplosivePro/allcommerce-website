<?php

namespace AllCommerce\Models\PaymentGateways;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class ClientEnabledPaymentProviders extends Model
{
    use CrudTrait, HasJsonRelationships, SoftDeletes, Uuid;

    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
        'merchant_id' => 'uuid',
        'client_id' => 'uuid',
        'shop_type' => 'uuid',
        'misc'=> 'array'
    ];

    public function payment_provider()
    {
        return $this->belongsTo('AllCommerce\Models\PaymentGateways\PaymentProviders', 'provider_id', 'id');
    }
}
