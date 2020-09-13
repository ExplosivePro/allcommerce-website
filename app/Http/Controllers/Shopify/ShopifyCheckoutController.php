<?php

namespace AnchorCMS\Http\Controllers\Shopify;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Cookie;
use AnchorCMS\Http\Controllers\Controller;

class ShopifyCheckoutController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkout($token)
    {
        $args = [];
        $data = $this->request->all();

        $args['data'] = $data;

        /*
        $url = 'https://'.$data['shop'].'/admin/api/2020-04/carts/'.$token.'.json';

        $response = Curl::to($url)
            ->withHeader('X-Shopify-Access-Token: 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')
            ->asJson(true)
            ->get();
        */

        return view('checkouts.concept', $args);
    }
}
