<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Transactions extends Model
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
        'order_uuid' => 'uuid',
        'shop_uuid' => 'uuid',
        'merchant_uuid' => 'uuid',
        'client_uuid' => 'uuid',
        'misc' => 'array',
    ];

    public function insert(array $schema)
    {
        $results = false;

        $model = new $this();
        foreach($schema as $col => $val)
        {
            $model->$col = $val;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function order()
    {
        return $this->belongsTo('AllCommerce\Orders', 'order_uuid', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo('AllCommerce\Merchants', 'merchant_uuid', 'id');
    }

    public function client()
    {
        return $this->belongsTo('AllCommerce\Clients', 'client_uuid', 'id');
    }
}
