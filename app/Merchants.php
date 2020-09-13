<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Merchants extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
        'client_id' => 'uuid'
    ];

    public function shops()
    {
        return $this->hasMany('AnchorCMS\Shops', 'merchant_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AnchorCMS\Clients', 'client_id', 'id');
    }

    public static function clientMerchants($client_id)
    {
        return self::whereClientId($client_id)->get();
    }

    public static function getActiveMerchant()
    {
        if(session()->has('active_merchant'))
        {
            $merchant = self::find(session()->get('active_merchant'));
        }
        else
        {
            $merchant = self::clientMerchants(backpack_user()->getActiveClientId())->first();

        }

        return $merchant;
    }
}
