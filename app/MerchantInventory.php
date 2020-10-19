<?php

namespace AllCommerce;

use AllCommerce\Shops;
use AllCommerce\Merchants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class MerchantInventory extends Model
{
    use SoftDeletes, Uuid;

    protected $hidden = ['deleted_at'];

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

    public function variants()
    {
        return $this->hasMany('AllCommerce\InventoryVariants', 'inventory_id', 'platform_id');
    }

    public function variant_options()
    {
        return $this->hasMany('AllCommerce\VariantsOptions', 'inventory_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('AllCommerce\InventoryImages', 'inventory_uuid', 'id');
    }

    public function images_by_platform()
    {
        return $this->hasMany('AllCommerce\InventoryImages', 'platform_id', 'platform_id');
    }

    public function getShopDefaultItem($shop_install_id)
    {
        $results = false;

        $record = $this->whereShopInstallId($shop_install_id)
                        ->whereDefaultItem(1)
                        ->whereActive(1)
                        ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getAllItemsByShopId($shop_install_id, $platform = 'allcommerce')
    {
        $results = false;

        $records = $this->whereShopInstallId($shop_install_id)
            ->wherePlatform($platform)
            ->get();

        if($records)
        {
            $results = $records;
        }

        return $results;
    }

    public function getItemByPlatformId($platform_id, $shop_install_id, $platform = 'allcommerce')
    {
        $results = false;

        $record = $this->whereShopInstallId($shop_install_id)
            ->wherePlatformId($platform_id)
            ->wherePlatform($platform)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
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
}
