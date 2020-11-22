<?php

namespace App\Models\SMS;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProviders extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'active'];

    public $incrementing = false;

    protected $casts = [

    ];

    public function provider_attributes()
    {
        return $this->hasMany('App\Models\SMS\SmsProviderAttributes', 'provider_id', 'id');
    }
}
