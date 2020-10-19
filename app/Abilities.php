<?php

namespace AllCommerce;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Abilities extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'title', 'client_id'];

    public function client()
    {
        return $this->hasOne('AllCommerceClients', 'id', 'client_id');
    }
}
