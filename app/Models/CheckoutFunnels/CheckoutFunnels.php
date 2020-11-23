<?php

namespace App\Models\CheckoutFunnels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Venturecraft\Revisionable\RevisionableTrait;

class CheckoutFunnels extends Model
{
    use CrudTrait, RevisionableTrait, SoftDeletes, Uuid;

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
    protected $fillable = ['shop_id', 'shop_install_id', 'funnel_name', 'shop_platform', 'default', 'active', 'client_id'];
    protected $guarded = [];

    protected $casts = [

    ];

    public function getAllActiveFunnels($platform, $shop_uuid)
    {
        $results = [];

        $records = $this->whereShopPlatform($platform)
            ->whereShopInstallId($shop_uuid)
            ->whereActive(1)
            ->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }

    public function insert(array $schema)
    {
        $results = false;

        $model = new $this();
        foreach($schema as $col => $val)
        {
            $model->$col = $val;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getDefaultFunnelByShop($platform, $shop_uuid)
    {
        $results = false;

        $record = $this->whereShopPlatform($platform)
            ->whereShopInstallId($shop_uuid)
            ->whereDefault(1)
            ->whereActive(1)
            ->first();

        if(!is_null($record) > 0)
        {
            $results = $record;
        }

        return $results;
    }

    public function funnel_attributes()
    {
        return $this->hasMany('App\Models\CheckoutFunnels\CheckoutFunnelAttributes', 'funnel_uuid', 'id');
    }

    public function product_item(int $num)
    {
        return $this->funnel_attributes()->whereFunnelAttribute('item-'.$num);
    }

    public function assigned_theme()
    {
        return $this->hasOne('App\Models\CheckoutFunnels\CheckoutFunnelAttributes', 'funnel_uuid', 'id')
            ->whereFunnelAttribute('blade-template')
            ->whereActive(1);
    }

    public function shop()
    {
        return $this->belongsTo('App\Models\Shops\Shop', 'shop_id','id');
    }

    public function shopify_install()
    {
        return $this->hasOne('App\Models\Shopify\ShopifyInstalls', 'id', 'shop_install_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }
}
