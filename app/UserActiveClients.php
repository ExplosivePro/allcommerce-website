<?php

namespace AnchorCMS;

use Illuminate\Database\Eloquent\Model;

class UserActiveClients extends Model
{
    protected $fillable = ['client_id', 'user_id'];
}
