<?php

namespace AnchorCMS;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileAppFeatures extends Model
{
    use Uuid, SoftDeletes;

    protected $table = 'mobile_app_features';

    protected $primaryKey = 'id';

    protected $casts = [
        'id' => 'uuid'
    ];

    public function mobile_app()
    {
        return $this->belongsTo('AnchorCMS\MobileApps', 'id', 'app_id');
    }
}
