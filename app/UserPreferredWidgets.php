<?php

namespace AllCommerce;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreferredWidgets extends Model
{
    use Uuid, SoftDeletes;

    protected $casts = [
        'id' => 'uuid'
    ];

    public function user()
    {
        return $this->belongsTo('AllCommerce\User', 'id', 'user_id');
    }

    public function widget()
    {
        return $this->belongsTo('AllCommerce\Widgets', 'id', 'user_id');
    }
}
