<?php

namespace App\Models\Utility;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class IconsSet extends Model
{
    use CrudTrait, Uuid, RevisionableTrait, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['icon','name', 'source'];

    public $incrementing = false;

    protected $revisionCreationsEnabled = true;

    public function identifiableName()
    {
        return $this->icon;
    }

    public function getSystemUserId()
    {
        return is_null(backpack_user()) ? 'System' : backpack_user()->id;
    }
}
