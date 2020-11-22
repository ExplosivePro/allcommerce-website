<?php

namespace App\Exceptions\Shops;

use DomainException;

class CouldNotAssignGatewayToShop extends DomainException
{
    public static function shopGatewayLimitReached(string $name)
    {
        return new static("Could not assign {$name}");
    }

    public static function shopSmsLimitReached(string $name)
    {
        return new static("Could not assign {$name}");
    }
}
