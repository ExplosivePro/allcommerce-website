<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    public $timestamps = false;

    public function ability()
    {
        return $this->hasOne('AnchorCMS\Abilities', 'id', 'ability_id');
    }
}
