<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Features extends Model
{
    use SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['client_id', 'name', 'allowed_roles', 'allowed_abilities', 'active'];

    protected $casts = [
        'id' => 'uuid'
    ];

    public function feature_attributes()
    {
        return $this->hasMany('AllCommerce\FeatureAttributes', 'feature_id', 'id');
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
