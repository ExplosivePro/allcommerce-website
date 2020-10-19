<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class MerchantApiTokens extends Model
{
    use HasJsonRelationships, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['token', 'client_id', 'token_type', 'scopes', 'active'];

    protected $casts = [
        'id' => 'uuid',
        'token' => 'uuid',
        'client_id' => 'uuid',
        'scopes' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_id', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo('AllCommerce\Merchants', 'scopes->merchant_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo('AllCommerce\Shops', 'scopes->shop_id', 'id');
    }

}
