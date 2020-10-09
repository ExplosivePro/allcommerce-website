<?php

namespace AnchorCMS\Models\SMS;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProviders extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'active'];

    protected $casts = [
        'id' => 'uuid',
    ];

    public function provider_attributes()
    {
        return $this->hasMany('AnchorCMS\Models\SMS\SmsProviderAttributes', 'provider_id', 'id');
    }
}