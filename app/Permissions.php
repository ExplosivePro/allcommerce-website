<?php

namespace AllCommerce;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    public $timestamps = false;

    public function ability()
    {
        return $this->hasOne('AllCommerce\Abilities', 'id', 'ability_id');
    }
}
