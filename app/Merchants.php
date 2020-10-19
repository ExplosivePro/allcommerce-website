<?php

namespace AllCommerce;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Merchants extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

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
        'client_id' => 'uuid',
    ];

    public function shops()
    {
        return $this->hasMany('AllCommerce\Shops', 'merchant_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_id', 'id');
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
