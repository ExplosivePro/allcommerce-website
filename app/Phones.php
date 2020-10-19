<?php

namespace AllCommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Phones extends Model
{
    use SoftDeletes, Uuid;

    protected $primaryKey  = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
        'shop_uuid' => 'uuid',
        'merchant_uuid' => 'uuid',
        'client_uuid' => 'uuid',
    ];

    public function loadNumber($number)
    {
        $results = false;

        $record = $this->wherePhone($number)->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function addNumber($number)
    {
        $results = false;

        $model = new $this;
        $model->phone = $number;
        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
