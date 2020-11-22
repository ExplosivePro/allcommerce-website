<?php

namespace App\Models;

use App\Aggregates\Clients\ClientAccountAggregate;
use App\Aggregates\Merchants\MerchantAggregate;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Merchant extends Model
{
    use CrudTrait, HasFactory, RevisionableTrait, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['name', 'active', 'client_id'];

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
        static::created(function ($merchant) {
            MerchantAggregate::retrieve($merchant->id)
                ->createMerchant($merchant->toArray())
                ->setNewMerchantApiToken($merchant->id, $merchant->client_id)
                ->persist();

            ClientAccountAggregate::retrieve($merchant->client_id)
                ->addMerchant($merchant->toArray())
                ->persist();
        });

        static::updated(function ($merchant) {
            MerchantAggregate::retrieve($merchant->id);
        });
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\MerchantDetails', 'merchant_id', 'id');
    }

    public function getSystemUserId()
    {
        return is_null(backpack_user()) ? 'System' : backpack_user()->id;
    }
}
