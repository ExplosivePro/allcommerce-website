<?php

namespace AnchorCMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class FeatureAttributes extends Model
{
    use SoftDeletes, Uuid;

    protected $casts = [
        'id' => 'uuid',
        'feature_id' => 'uuid'
    ];
}
