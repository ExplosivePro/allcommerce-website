<?php

namespace App\Models\API;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class MerchantApiToken extends Model
{
    use HasJsonRelationships, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['token', 'client_id', 'token_type', 'scopes', 'active'];

    protected $casts = [
        'scopes' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    /*
    public function merchant()
    {
        return $this->belongsTo('AllCommerce\Merchants', 'scopes->merchant_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo('AllCommerce\Shops', 'scopes->shop_id', 'id');
    }
    */
}
