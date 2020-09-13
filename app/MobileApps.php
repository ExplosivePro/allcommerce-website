<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class MobileApps extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $table = 'mobile_apps';

    protected $primaryKey = 'id';

    protected $casts = [
        'id' => 'uuid'
    ];

    public function app_features()
    {
        return $this->hasMany('AnchorCMS\MobileAppFeatures', 'app_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AnchorCMS\Clients', 'client_id', 'id');
    }

    public function getAllAppWithPushNotes($client_id = '', $active = 1)
    {
        $results = [];

        $records = MobileApps::whereClientId($client_id)
            ->whereActive($active)
            ->with('app_features')
            ->get();

        if(count($records) > 0)
        {
            foreach ($records as $app)
            {
                if($app->notes_driver != 'none')
                {
                    if(count($app->app_features) > 0)
                    {
                        foreach($app->app_features as $feature)
                        {
                            if($feature->feature == 'API URL')
                            {
                                $results[] = $app;
                                break;
                            }
                        }
                    }

                }
            }
        }

        return $results;
    }
}
