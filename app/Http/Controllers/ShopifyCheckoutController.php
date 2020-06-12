<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ShopifyCheckoutController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkout()
    {
        $data = $this->request->all();

        $value = $_COOKIE['cart'];
        echo($_COOKIE['cart']);
    }
}
