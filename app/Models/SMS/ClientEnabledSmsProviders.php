<?php

namespace App\Models\SMS;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class ClientEnabledSmsProviders extends Model
{
    use CrudTrait, HasJsonRelationships, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $fillable = ['client_id', 'provider_id', 'misc', 'active'];

    protected $casts = [
        'misc' => 'array'
    ];

    public function sms_provider()
    {
        return $this->belongsTo('App\Models\SMS\SmsProviders', 'provider_id', 'id');
    }
}
