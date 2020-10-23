<?php

namespace AllCommerce\Http\Controllers\API\Checkouts\Upsells;

use Illuminate\Http\Request;
use AllCommerce\Http\Controllers\Controller;

class UpsellDownSellAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function check_for_enabled_upsells()
    {
        $results = ['success' => false];

        if(true)
        {
            // @todo - set this up when ready to suppose the up and down sale stream
            $results = ['success'=> true, 'upsells' => 0];
        }
        return response($results);
    }
}
