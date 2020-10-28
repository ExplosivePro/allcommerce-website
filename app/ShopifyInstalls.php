<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class ShopifyInstalls extends Model
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
    ];

    public function merchant()
    {
        return $this->hasOne('AllCommerce\Merchants', 'uuid', 'merchant_uuid');
    }

    public function client()
    {
        return $this->hasOne('AllCommerce\Clients', 'uuid', 'client_id');
    }

    public function shop()
    {
        return $this->hasOne('AllCommerce\Shops', 'uuid', 'shop_uuid');
    }

    public function shop_by_url()
    {
        return $this->hasOne('AllCommerce\Shops', 'shop_url', 'shopify_store_url');
    }

    public static function isInstalled($shop_id)
    {
        $results = false;

        $record = self::whereShopUuid($shop_id)->whereInstalled(1)->first();

        if(!is_null($record))
        {
            $results = true;
        }

        return $results;
    }

    /*
    public function merchant_inventory()
    {
        return $this->hasMany('App\MerchantInventory', 'shop_install_id', 'uuid');
    }
    */
}
