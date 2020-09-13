<?php

namespace AnchorCMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Features extends Model
{
    use SoftDeletes, Uuid;

    protected $casts = [
        'id' => 'uuid'
    ];

    public function feature_attributes()
    {
        return $this->hasMany('AnchorCMS\FeatureAttributes', 'feature_id', 'id');
    }

    public function getFeature($name, $client_id)
    {
        $results = false;

        $record = $this->whereName($name)
            ->whereClientId($client_id)
            ->whereActive(1)
            ->with('feature_attributes')
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }
}
