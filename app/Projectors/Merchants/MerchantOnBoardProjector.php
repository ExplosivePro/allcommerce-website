<?php

namespace AllCommerce\Projectors\Merchants;

use AllCommerce\Events\Merchants\MerchantDeleted;
use AllCommerce\MerchantApiTokens;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MerchantOnBoardProjector extends Projector
{
    public function onMerchantDeleted(MerchantDeleted $event)
    {
        $prev_token = MerchantApiTokens::whereTokenType('merchant')
            //->whereClientId($client_id)
            ->where('scopes->merchant_id', '=', $event->getMerchant())
            ->first();

        if(!is_null($prev_token))
        {
            $prev_token->active = 0;
            $prev_token->save();
            
            $prev_token->delete();
        }
    }
}
