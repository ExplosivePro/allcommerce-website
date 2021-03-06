<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\BouncerFacade as Bouncer;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Clients extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'active'];

    protected $casts = [
        'id' => 'uuid'
    ];

    public static function getAllClientsDropList()
    {
        $results = ['Select a Client Account'];

        $records = self::all();
        $host_uuid = self::getHostClient();

        if(count($records) > 0)
        {
            foreach ($records as $client) {
                if(($client->uuid == $host_uuid) && (backpack_user()->client_id == $host_uuid))
                {
                    $results[$client->id] = $client->name;
                }
                elseif(backpack_user()->client_id == $client->id)
                {
                    $results[$client->id] = $client->name;
                }
                elseif(Bouncer::is(backpack_user())->a('god', 'admin'))
                {
                    $results[$client->id] = $client->name;
                }
            }
        }

        return $results;
    }

    public static function getHostClient()
    {
        $results = false;

        $model = self::whereName(env('HOST_CLIENT'))->first();

        if(!is_null($model))
        {
            $results = $model->id;
        }

        return $results;
    }

    public function features()
    {
        return $this->hasMany('AllCommerce\Features', 'client_id', 'id');
    }

    public function merchants()
    {
        return $this->hasMany('AllCommerce\Merchants', 'client_id', 'id');
    }

    public function shops()
    {
        return $this->hasMany('AllCommerce\Shops', 'client_id', 'id');
    }
}
