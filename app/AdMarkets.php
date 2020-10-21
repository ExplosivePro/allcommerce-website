<?php

namespace AllCommerce;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class AdMarkets extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $fillable = ['client_id', 'market_name', 'active'];

    protected $casts = [
        'id' => 'uuid'
    ];

    public function client()
    {
        return $this->hasOne('AllCommerce\Clients', 'id', 'client_id');
    }
}
