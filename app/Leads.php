<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Leads extends Model
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

    public function shop()
    {
        return $this->belongsTo('AllCommerce\Shops', 'shop_uuid', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_uuid', 'id');
    }

    public function order()
    {
        return $this->hasOne('AllCommerce\Orders', 'id', 'order_uuid');
    }

    public function lead_attributes()
    {
        return $this->hasMany('AllCommerce\LeadAttributes', 'lead_uuid', 'id');
    }

    public function shipping_address()
    {
        return $this->hasOne('AllCommerce\ShippingAddresses', 'lead_uuid', 'id');
    }

    public function billing_address()
    {
        return $this->hasOne('AllCommerce\BillingAddresses', 'lead_uuid', 'id');
    }
}
