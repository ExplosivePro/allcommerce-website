<?php

namespace App\Models\Shops;

use App\Aggregates\Clients\ClientAccountAggregate;
use App\Aggregates\Merchants\MerchantAggregate;
use App\Aggregates\Shops\ShopConfigAggregate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Shop extends Model
{
    use CrudTrait, HasFactory, HasJsonRelationships, RevisionableTrait, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['name', 'shop_url','shop_type','merchant_id','active', 'client_id'];

    protected $revisionCreationsEnabled = true;

    public function identifiableName()
    {
        return $this->name;
    }

    public static function boot()
    {
        parent::boot();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($shop) {
            $s_aggy = ShopConfigAggregate::retrieve($shop->id)
                ->createShop($shop->toArray())
                ->setNewShopApiToken($shop->id, $shop->client_id);

            $client = $shop->client()->first();
            $sms_enabled = $client->enabled_sms()->first();

            if(!is_null($sms_enabled))
            {
                if($sms_enabled->active == 1)
                {
                    $s_aggy->setSMSConfigured();
                }
            }
            $s_aggy->persist();


            MerchantAggregate::retrieve($shop->merchant_id)
                ->addShop($shop->toArray())
                ->persist();

            ClientAccountAggregate::retrieve($shop->client_id)
                ->addShop($shop->toArray())
                ->persist();
        });

        static::updated(function ($shop) {
            ShopConfigAggregate::retrieve($shop->id);
        });
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id', 'id');
    }

    public function shop_type_model()
    {
        return $this->hasOne('App\Models\Shops\ShopTypes', 'id', 'shop_type');
    }

    public function shoptype()
    {
        return $this->shop_type_model();
    }

    public function oauth_api_token()
    {
        return $this->hasOne('App\Models\API\MerchantApiToken', 'scopes->shop_id', 'id');
    }

    public function client_enabled_payment_providers()
    {
        return $this->hasMany('App\Models\PaymentGateways\ClientEnabledPaymentProviders', 'client_id', 'client_id')
            ->with('payment_provider');
    }

    public function shop_assigned_payment_providers()
    {
        return $this->hasMany('App\Models\PaymentGateways\ShopAssignedPaymentProviders', 'shop_uuid', 'id')
            ->with('payment_provider');
    }

    public function shop_assigned_sms_provider()
    {
        return $this->hasOne('App\Models\SMS\ShopAssignedSmsProviders', 'shop_uuid', 'id');
    }

    public function shopify_install()
    {
        return $this->hasOne('App\Models\Shopify\ShopifyInstalls', 'shop_uuid', 'id');
    }

    public function getSystemUserId()
    {
        return is_null(backpack_user()) ? 'System' : backpack_user()->id;
    }
}
