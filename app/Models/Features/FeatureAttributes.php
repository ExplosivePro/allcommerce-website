<?php

namespace App\Models\Features;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class FeatureAttributes extends Model
{
    use HasJsonRelationships, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'attribute_desc_misc' => 'array'
    ];
}
