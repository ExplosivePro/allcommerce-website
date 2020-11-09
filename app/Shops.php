<?php

namespace AllCommerce;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Shops extends Model
{
    use CrudTrait, HasJsonRelationships, SoftDeletes, Uuid;

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

    protected $fillable = [
        'name', 'shop_url', 'shop_type', 'merchant_id', 'client_id', 'active'
    ];

    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
        'merchant_id' => 'uuid',
        'client_id' => 'uuid',
        'shop_type' => 'uuid'
    ];

    public function merchant()
    {
        return $this->belongsTo('AllCommerce\Merchants', 'merchant_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_id', 'id');
    }

    public function shop_type()
    {
        return $this->belongsTo('AllCommerce\ShopTypes', 'shop_type', 'id');
    }

    public function shoptype()
    {
        return $this->shop_type();
    }

    public function inventory()
    {
        return $this->hasMany('AllCommerce\MerchantInventory', 'shop_id', 'id');
    }

    public function shopify_install()
    {
        return $this->hasOne('AllCommerce\ShopifyInstalls', 'shop_uuid', 'id');
    }

    public function oauth_api_token()
    {
        return $this->hasOne('AllCommerce\MerchantApiTokens', 'scopes->shop_id', 'id');
    }

    public function shop_assigned_payment_providers()
    {
        return $this->hasMany('AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders', 'shop_uuid', 'id')
            ->with('payment_provider');
    }

    public function client_enabled_payment_providers()
    {
        return $this->hasMany('AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders', 'client_id', 'client_id')
            ->with('payment_provider');
    }
}
