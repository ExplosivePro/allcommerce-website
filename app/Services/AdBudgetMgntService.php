<?php

namespace AnchorCMS\Services;

use AnchorCMS\AdMarkets;

class AdBudgetMgntService
{
    protected $markets;

    public function __construct(AdMarkets $markets)
    {
        $this->markets = $markets;
    }

    public function getMarketCRUDDropdownOptions($client_id)
    {
        $results = ['Select a Market'];

        $markets = $this->markets->where('client_id', '=', $client_id)->get();

        if(count($markets) > 0)
        {
            foreach($markets as $market)
            {
                $results[$market->id] = $market->market_name;
            }
        }

        return $results;
    }

}
