<?php

namespace AllCommerce\Exceptions\SMS;

use Exception;

class CouldNotSendSMS extends Exception
{
    public static function phoneNumberBlocked($number, $type) : self
    {
        return new static("Phone number {$number} blocked via {$type} limits");
    }
}
