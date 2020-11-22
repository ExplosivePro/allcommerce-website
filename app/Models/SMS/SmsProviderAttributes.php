<?php

namespace App\Models\SMS;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProviderAttributes extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['provider_id', 'name', 'value', 'misc', 'active'];

    public $incrementing = false;

    protected $casts = [
        'misc' => 'array'
    ];
}
