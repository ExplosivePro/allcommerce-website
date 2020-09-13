<?php

namespace AnchorCMS;

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
        return $this->belongsTo('AnchorCMS\User', 'id', 'user_id');
    }

    public function widget()
    {
        return $this->belongsTo('AnchorCMS\Widgets', 'id', 'user_id');
    }
}
