<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Orders extends Model
{
    use SoftDeletes, Uuid;

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
        'reference_uuid' => 'uuid',
        'shipping_uuid' => 'uuid',
        'billing_uuid' => 'uuid',
        'order_uuid' => 'uuid',
        'shop_uuid' => 'uuid',
        'merchant_uuid' => 'uuid',
        'client_uuid' => 'uuid',
        'misc' => 'collection',
    ];

    public function lead()
    {
        return $this->hasOne('AllCommerce\Leads', 'order_uuid', 'id');
    }

    public function attributes()
    {
        return $this->hasMany('AllCommerce\OrderAttributes', 'order_uuid', 'id');
    }

    public function shop()
    {
        return $this->belongsTo('AllCommerce\Shops', 'shop_uuid', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo('AllCommerce\Merchants', 'merchant_uuid', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_uuid', 'id');
    }

    public function shipping_address()
    {
        return $this->hasOne('AllCommerce\ShippingAddresses', 'order_uuid', 'id');
    }

    public function billing_address()
    {
        return $this->hasOne('AllCommerce\BillingAddresses', 'order_uuid', 'id');
    }

    public function email_record()
    {
        return $this->hasOne('AllCommerce\Emails', 'email', 'email');
    }

    public function shop_install()
    {
        return $this->belongsTo('AllCommerce\ShopifyInstalls', 'shop_uuid', 'shop_uuid');
    }
}
