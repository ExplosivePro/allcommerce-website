<?php

namespace AllCommerce\Models\SMS;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProviderAttributes extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'active'];

    protected $casts = [
        'id' => 'uuid',
        'provider_id' => 'uuid',
        'misc' => 'array'
    ];
}
