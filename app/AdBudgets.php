<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class AdBudgets extends Model
{
    use CrudTrait,SoftDeletes, Uuid;

    protected $fillable = ['client_id', 'market_id', 'club_id', 'location_name','facebook_ig_budget', 'google_budget', 'active'];

    protected $casts = [
        'id' => 'uuid'
    ];

    public function client()
    {
        return $this->hasOne('AnchorCMS\Clients', 'id', 'client_id');
    }

    public function market()
    {
        return $this->hasOne('AnchorCMS\AdMarkets', 'id', 'market_id');
    }
}
