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

        var_dump($data);
    }
}
