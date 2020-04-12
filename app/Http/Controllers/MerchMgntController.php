<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;

class MerchMgntController extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Show the Merch Mgnt dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }
}
