<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDetails extends Model
{
    use CrudTrait, HasFactory, SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['client_id', 'name', 'value', 'misc'];

    protected $casts = [
        'misc' => 'array'
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }
}
