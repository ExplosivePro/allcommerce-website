<?php

namespace App\Http\Controllers;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ShopifyCheckoutController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkout($token)
    {
        $data = $this->request->all();
        $url = 'https://'.$data['shop'].'/admin/api/2020-04/carts/'.$token.'.json';
        $response = Curl::to($url)
            ->withHeader('X-Shopify-Access-Token: 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')
            ->asJson(true)
            ->get();

        echo 'AllCommerce presents - a future checkout page with this cart!';
        var_dump($response);
    }
}
