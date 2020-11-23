<?php

namespace App\Models\Shopify;

use Ramsey\Uuid\Uuid as RUuid;
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

    ];

    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_uuid');
    }

    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    public function shop()
    {
        return $this->hasOne('App\Models\Shops\Shop', 'id', 'shop_uuid');
    }

    public function shop_by_url()
    {
        return $this->hasOne('App\Models\Shops\Shop', 'shop_url', 'shopify_store_url');
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

    public function getByShopUrl($url)
    {
        $results = false;

        $record = $this->whereShopifyStoreUrl($url)->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function insertNonceRecord($shop_url)
    {
        $results = false;

        $record = new $this();
        $record->nonce = RUuid::uuid4();
        $record->shopify_store_url = $shop_url;

        if($record->save())
        {
            $results = $record;
        }

        return $results;
    }

    public function signed_in_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'logged_in_user');
    }
    /*
    public function merchant_inventory()
    {
        return $this->hasMany('App\MerchantInventory', 'shop_install_id', 'uuid');
    }
    */
}
