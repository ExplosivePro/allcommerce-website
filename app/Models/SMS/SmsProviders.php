<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class SmsProviders extends Model
{
    use CrudTrait, RevisionableTrait, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['name', 'active'];

    protected $casts = [

    ];

    public function provider_attributes()
    {
        return $this->hasMany('App\Models\SMS\SmsProviderAttributes', 'provider_id', 'id');
    }
}
